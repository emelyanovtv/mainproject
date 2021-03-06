<?php
namespace Pingpong\Admin\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Pingpong\Admin\Entities\StorageEvents;
use Pingpong\Admin\Entities\StorageEventsMaterials;
use Pingpong\Admin\Entities\StorageHasEventsMaterials;
use Pingpong\Admin\Entities\StorageHasMaterial;
use Pingpong\Admin\Entities\Storages;

class OperationsController extends BaseController {

    protected $operations;

    public function __construct(StorageEventsMaterials $operations)
    {
        $this->operations = $operations;
    }
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        return $this->view('operations.index');
	}

    private static function cmpArr($a, $b) {
        if ((int) $a['materials']['materialsgroup']['id'] == (int) $b['materials']['materialsgroup']['id']) {
            return 0;
        }
        return ((int) $a['materials']['materialsgroup']['id'] < (int) $b['materials']['materialsgroup']['id']) ? -1 : 1;
    }

    public function showoperations($storage_id = null, $date = null, $material_id = null)
    {
        $storage_id = intval($storage_id);
        $user = Auth::user();
        $material_id = intval($material_id);
        $dateStr = strval($date);

        if(!strlen($dateStr))
            $dateStr = date('Y-m');


        //first case if we dont have not storage and material
        $storages = ($user->isAdmin()) ? Storages::all() : Storages::where('id',  Config::get('admin::custom.storage'))->get();
        $storages_list = array('all' => "Все") + $storages->lists("name", "id");
        $materials_storage = array();

        foreach($storages as $storage)
        {
            if($storage->hasMaterials)
            {
                foreach($storage->hasMaterials as $data)
                {
                    $materials_storage[$storage->id][null] = "Нет";
                    $materials_storage[$storage->id][$data->materials->materialsgroup->name][$data->materials->id] = $data->materials->name;
                }
            }
        }


        $dateArr = explode("-", $dateStr);
        $dayInMonth = cal_days_in_month(CAL_GREGORIAN, $dateArr[1], $dateArr[0]);
        $storagesArrData = array();
        $dataPlus = array();
        $storeagesObj = Storages::with(array('hasMaterials.materials.materialsgroup' => function($query) {
                $query->orderBy('material_groups.id', 'ASC');

            }));
        $list = ($user->isAdmin()) ? $storeagesObj->get() : $storeagesObj->where("id", "=",  Config::get('admin::custom.storage'))->get();
        if($storage_id > 0 && $material_id <= 0)
        {

            //custom data

            $list = Storages::with(array('hasMaterials.materials.materialsgroup'))->where("id", "=", $storage_id)->get();
            //custom
            $dataPlus = compact('storage_id');

        }
        elseif($storage_id > 0 && $material_id > 0)
        {
            $list = Storages::with(array('hasMaterials' => function($query) use ($material_id)
                                            {
                                                $query->where('material_id', '=', $material_id);

                                            },
                                        'hasMaterials.materials.materialsgroup'
                                        )
                                   )->where("id", "=", $storage_id)->get();
            $dataPlus = compact('storage_id','material_id');
        }
        foreach($list as $storage)
        {

            $storagesArrData[$storage->id] = array();

            $storageMainData = $storage->toArray();
            unset($storageMainData['has_materials']);

            $storagesArrData[$storage->id]['data'] = $storageMainData;

            if(count($storage->hasMaterials))
            {
                $products = array();
                foreach($storage->hasMaterials as $nump => $product)
                {
                    $prodArr = $product->toArray();
                    $events = array();
                    if(count($prodArr))
                    {
                        $material = StorageHasMaterial::find($prodArr['id']);
                        $eventsForProduct = $material->events()->where('created_at', '>=', $dateStr."-01 00:00:01")->where('created_at', '<=', $dateStr."-".$dayInMonth." 23:59:59")->get();

                        if($eventsForProduct != null && count($eventsForProduct))
                        {
                            foreach($eventsForProduct as $num => $eventData)
                            {
                                $dataArr = $eventData->toArray();
                                $dateArrEvent = explode(" ", $dataArr['created_at']);
                                $events[$num] = $eventData->toArray();
                                $events[$num]['date'] = $dateArrEvent[0];
                                if($eventValue = $eventData->event->load('eventData.properties.property.measure'))
                                {
                                    $dataArrSet = $eventValue->toArray();

                                    if(isset($dataArrSet['data']) && strlen(trim($dataArrSet['data'])))
                                        $dataArrSet['data'] = unserialize($dataArrSet['data']);
                                    else
                                        $dataArrSet['data'] = null;


                                    if(isset($dataArrSet['event_data']['properties']) && count($dataArrSet['event_data']['properties']) && count($dataArrSet['data']))
                                    {
                                        foreach($dataArrSet['event_data']['properties'] as $number => $propData)
                                        {
                                            if(isset($propData['property']))
                                                if(array_key_exists((int) $propData['property']['id'], $dataArrSet['data']))
                                                    $dataArrSet['event_data']['properties'][$number]['property']['value'] = $dataArrSet['data'][$propData['property']['id']];
                                        }
                                    }


                                    $events[$num]['data'] = $dataArrSet;
                                }
                            }

                        }
                    }

                    $products[$nump] = $prodArr;
                    $products[$nump]['events'] = $events;
                }



                if(count($products))
                {
                    foreach($products as $num => $prod)
                    {
                        if(isset($prod['events']) && count($prod['events']))
                        {
                            $lastEvent = $prod['events'][count($prod['events']) - 1];
                            $total = $products[$num]['total_end'] = $products[$num]['total_begin'] = (int) $lastEvent['total_value'];

                            foreach($prod['events'] as $eventNum => $eventDataArray)
                            {
                                if(isset($eventDataArray['data']['event_data']['char']))
                                {
                                    switch($eventDataArray['data']['event_data']['char'])
                                    {
                                        case "+":
                                            $products[$num]['total_begin'] = $products[$num]['total_begin'] - (int) $eventDataArray['data']['value'];
                                            break;
                                        case "-":
                                            $products[$num]['total_begin'] = $products[$num]['total_begin'] + (int) $eventDataArray['data']['value'];
                                            break;
                                        case "~":
                                            if((int) $eventDataArray['data']['data']['from_storage']['id'] == (int) $eventDataArray['data']['storage_id'])
                                            {
                                                $prod['events'][$eventNum]['data']['event_data']['char'] = "-";
                                                $products[$num]['total_begin'] = $products[$num]['total_begin'] + (int) $eventDataArray['data']['value'];
                                            }
                                            elseif((int) $eventDataArray['data']['data']['to_storage']['id'] == (int) $eventDataArray['data']['storage_id'])
                                            {
                                                $prod['events'][$eventNum]['data']['event_data']['char'] = "+";
                                                $products[$num]['total_begin'] = $products[$num]['total_begin'] - (int) $eventDataArray['data']['value'];
                                            }
                                            break;

                                    }
                                }
                            }

                            $newEventsArr = array();
                            foreach($prod['events'] as $eventPrepare)
                            {
                                if(isset($eventPrepare['data']['event_data']['char']) && strlen(trim($eventPrepare['data']['event_data']['char'])))
                                    $newEventsArr[$eventPrepare['date']][$eventPrepare['data']['event_data']['char']][] = $eventPrepare;
                                else
                                    $newEventsArr[$eventPrepare['date']]['custom'][] = $eventPrepare;
                            }
                            unset($products[$num]['events']);
                            $products[$num]['events'] = $newEventsArr;
                        }
                        else
                        {

                            $d = \DateTime::createFromFormat('Y-m', $dateStr);
                            $l = \DateTime::createFromFormat('Y-m', $dateStr);

                            $firstDay = $d->modify('+1 month')->modify('first day of this month');
                            $lastDay = $l->modify('+1 month')->modify('last day of this month');


                            $materialCustom = StorageHasMaterial::find($prod['id']);
                            $eventsForProductIFNotExist = $materialCustom->events()->where('created_at', '>=', $firstDay->format("Y-m-d"))->where('created_at', '<=', $lastDay->format("Y-m-d"))->first();

                            if($eventsForProductIFNotExist)
                                $products[$num]['total_end'] = $products[$num]['total_begin'] = 0;
                            else
                            {
                                if($firstDay->getTimestamp() < time())
                                    $products[$num]['total_end'] = $products[$num]['total_begin'] = 0;
                                else
                                    $products[$num]['total_end'] = $products[$num]['total_begin'] = $prod['total'];
                            }


                        }

                    }

                }
                if(count($products))
                {
                    $newProductArr = array();
                    foreach($products as $numS => $prod)
                    {
                        $newProductArr[$prod['materials']['materialsgroup']['name']][$numS] = $prod;
                    }

                    $products = $newProductArr;
                }

                $storagesArrData[$storage->id]['products'] = $products;
                unset($products, $storage['has_materials']);
            }
        }

        $data = compact('storages_list', 'materials_storage','dateStr','dayInMonth','storagesArrData');


        $data = array_merge($data, $dataPlus);

        return $this->view('operations.show', $data);

    }


    public function customshowoperations($storage_id = null, $date_from = null,  $date_to = null, $material_id = null)
    {
        //echo "<pre>";var_dump($date_from);echo "</pre>";die();
        $storage_id = intval($storage_id);
        $user = Auth::user();
        $material_id = intval($material_id);
        $dateFrom = strval($date_from);
        $dateTo = strval($date_to);

        if(!strlen($dateFrom))
            $dateFrom = date('Y-m', strtotime("-3 month"));
        if(!strlen($dateTo))
            $dateTo = date('Y-m');


        //first case if we dont have not storage and material
        $storages = ($user->isAdmin()) ? Storages::all() : Storages::where('id',  Config::get('admin::custom.storage'))->get();
        $storages_list = array('all' => "Все") + $storages->lists("name", "id");
        $materials_storage = array();

        foreach($storages as $storage)
        {
            if($storage->hasMaterials)
            {
                foreach($storage->hasMaterials as $data)
                {
                    $materials_storage[$storage->id][null] = "Нет";
                    $materials_storage[$storage->id][$data->materials->materialsgroup->name][$data->materials->id] = $data->materials->name;
                }
            }
        }


        $dateArr = explode("-", $dateTo);
        $dayInMonth = cal_days_in_month(CAL_GREGORIAN, $dateArr[1], $dateArr[0]);
        $storagesArrData = array();
        $dataPlus = array();
        $storeagesObj = Storages::with(array('hasMaterials.materials.materialsgroup' => function($query) {
                $query->orderBy('material_groups.id', 'ASC');

            },'hasMaterials.materials.values.entetyprop.property' ));
        $list = ($user->isAdmin()) ? $storeagesObj->get() : $storeagesObj->where("id", "=",  Config::get('admin::custom.storage'))->get();
        if($storage_id > 0 && $material_id <= 0)
        {

            //custom data

            $list = Storages::with(array('hasMaterials.materials.materialsgroup'))->where("id", "=", $storage_id)->get();
            //custom
            $dataPlus = compact('storage_id');

        }
        elseif($storage_id > 0 && $material_id > 0)
        {
            $list = Storages::with(array('hasMaterials' => function($query) use ($material_id)
                    {
                        $query->where('material_id', '=', $material_id);

                    },
                    'hasMaterials.materials.materialsgroup'
                )
            )->where("id", "=", $storage_id)->get();
            $dataPlus = compact('storage_id','material_id');
        }
        foreach($list as $storage)
        {

            $storagesArrData[$storage->id] = array();

            $storageMainData = $storage->toArray();
            unset($storageMainData['has_materials']);

            $storagesArrData[$storage->id]['data'] = $storageMainData;

            if(count($storage->hasMaterials))
            {
                $products = array();
                foreach($storage->hasMaterials as $nump => $product)
                {
                    $prodArr = $product->toArray();
                    $events = array();
                    if(count($prodArr))
                    {
                        $material = StorageHasMaterial::find($prodArr['id']);
                        $eventsForProduct = $material->events()->where('created_at', '>=', $dateFrom."-01 00:00:01")->where('created_at', '<=', $dateTo."-".$dayInMonth." 23:59:59")->get();

                        if($eventsForProduct != null && count($eventsForProduct))
                        {
                            foreach($eventsForProduct as $num => $eventData)
                            {
                                $dataArr = $eventData->toArray();
                                $dateArrEvent = explode(" ", $dataArr['created_at']);
                                $events[$num] = $eventData->toArray();
                                $events[$num]['date'] = $dateArrEvent[0];
                                if($eventValue = $eventData->event->load('eventData.properties.property.measure'))
                                {
                                    $dataArrSet = $eventValue->toArray();

                                    if(isset($dataArrSet['data']) && strlen(trim($dataArrSet['data'])))
                                        $dataArrSet['data'] = unserialize($dataArrSet['data']);
                                    else
                                        $dataArrSet['data'] = null;


                                    if(isset($dataArrSet['event_data']['properties']) && count($dataArrSet['event_data']['properties']) && count($dataArrSet['data']))
                                    {
                                        foreach($dataArrSet['event_data']['properties'] as $number => $propData)
                                        {
                                            if(isset($propData['property']))
                                                if(array_key_exists((int) $propData['property']['id'], $dataArrSet['data']))
                                                    $dataArrSet['event_data']['properties'][$number]['property']['value'] = $dataArrSet['data'][$propData['property']['id']];
                                        }
                                    }


                                    $events[$num]['data'] = $dataArrSet;
                                }
                            }

                        }
                    }

                    $products[$nump] = $prodArr;
                    $products[$nump]['events'] = $events;
                }



                if(count($products))
                {
                    foreach($products as $num => $prod)
                    {
                        if(isset($prod['events']) && count($prod['events']))
                        {
                            $lastEvent = $prod['events'][count($prod['events']) - 1];
                            $total = $products[$num]['total_end'] = $products[$num]['total_begin'] = (int) $lastEvent['total_value'];

                            foreach($prod['events'] as $eventNum => $eventDataArray)
                            {
                                if(isset($eventDataArray['data']['event_data']['char']))
                                {
                                    switch($eventDataArray['data']['event_data']['char'])
                                    {
                                        case "+":
                                            $products[$num]['total_begin'] = $products[$num]['total_begin'] - (int) $eventDataArray['data']['value'];
                                            break;
                                        case "-":
                                            $products[$num]['total_begin'] = $products[$num]['total_begin'] + (int) $eventDataArray['data']['value'];
                                            break;
                                        case "~":
                                            if((int) $eventDataArray['data']['data']['from_storage']['id'] == (int) $eventDataArray['data']['storage_id'])
                                            {
                                                $prod['events'][$eventNum]['data']['event_data']['char'] = "-";
                                                $products[$num]['total_begin'] = $products[$num]['total_begin'] + (int) $eventDataArray['data']['value'];
                                            }
                                            elseif((int) $eventDataArray['data']['data']['to_storage']['id'] == (int) $eventDataArray['data']['storage_id'])
                                            {
                                                $prod['events'][$eventNum]['data']['event_data']['char'] = "+";
                                                $products[$num]['total_begin'] = $products[$num]['total_begin'] - (int) $eventDataArray['data']['value'];
                                            }
                                            break;

                                    }
                                }
                            }

                            $newEventsArr = array();
                            foreach($prod['events'] as $eventPrepare)
                            {
                                if(isset($eventPrepare['data']['event_data']['char']) && strlen(trim($eventPrepare['data']['event_data']['char'])))
                                    $newEventsArr[$eventPrepare['data']['event_data']['char']][] = $eventPrepare;
                                else
                                    $newEventsArr['custom'][] = $eventPrepare;
                            }
                            unset($products[$num]['events']);
                            $products[$num]['events'] = $newEventsArr;
                        }

                    }

                }
                $prodArrGroups = [2,11,8,3];
                if(count($products))
                {
                    $newProductArr = array();
                    foreach($products as $numS => $prod)
                    {
                        if(in_array((int) $prod['materials']['material_group_id'], $prodArrGroups))
                        {
                            $sq = 0;
                            if(count($prod['materials']['values']) >= 2)
                            {
                                $width = $prod['materials']['values'][0]['value'];
                                $length = $prod['materials']['values'][1]['value'];
                                $sq = $width*$length;
                            }
                            $totalValueInt = 0;
                            if(isset($prod['events']["-"]) && count($prod['events']["-"]) > 0)
                            {
                                foreach($prod['events']["-"] as $event)
                                {
                                    $totalValueInt+= (int) $event['data']['value'];
                                }
                            }
                            $totalValueBuy = 0;
                            if(isset($prod['events']["+"]) && count($prod['events']["+"]) > 0)
                            {
                                foreach($prod['events']["+"] as $event)
                                {
                                    $totalValueBuy+= (int) $event['data']['event_data']['properties'][1]['property']['value'];
                                }
                            }
                            if($totalValueBuy > 0)
                                $prod['zakup'] = $totalValueBuy;

                            if($sq > 0 && $totalValueInt > 0)
                            {

                                $prod['expense'] = $sq*$totalValueInt;
                            }

                            $newProductArr[$prod['materials']['materialsgroup']['name']][$numS] = $prod;
                        }
                    }

                    $products = $newProductArr;
                }

                $storagesArrData[$storage->id]['products'] = $products;
                unset($products, $storage['has_materials']);
            }
        }

        $data = compact('storages_list', 'materials_storage','dateFrom','dateTo','dayInMonth','storagesArrData');

        $data = array_merge($data, $dataPlus);
        return $this->view('operations.showcustom', $data);

    }




	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        $user = Auth::user();
        $storages = ($user->isAdmin()) ? Storages::all() : Storages::where('id',  Config::get('admin::custom.storage'))->get();
        $events = ($user->isAdmin()) ? StorageEvents::all() : StorageEvents::where('id',  Config::get('admin::custom.event'))->get();
        $storages_list = array("NULL" => "Нет") + $storages->lists('name', 'id');
        $events_list = array("NULL" => "Нет") + $events->lists('name', 'id');
        $materials_storage = array();
        $events_props = array();

        $user_id = $user->id;

        foreach($events as $event)
        {
            if($event->char)
            {
                foreach($event->properties as $data)
                {
                    $events_props[$event->id][] = $data->property;
                }
            }
        }


        foreach($storages as $storage)
        {
            if($storage->hasMaterials)
            {
                foreach($storage->hasMaterials as $data)
                {
                    $materials_storage[$storage->id][$data->materials->materialsgroup->name][$data->materials->id] = $data->materials->name;
                }
            }
        }


        $events = $events->toArray();
        $new_events = array();
        foreach($events as $event)
            $new_events[$event['id']] = $event;

        unset($events);

        return $this->view('operations.create', compact('storages_list', 'materials_storage', 'new_events', 'events_list','events_props','user_id'));
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
        $data 		= $this->inputAll();
        $rules      = $this->operations->getRules();
        $data['value'] = (isset($data['value'])) ? ( ( ( int ) $data['value'] > 0 ) ? intval($data['value']) : "" ) : "";
        $boolIStransfer = (isset($data['to_storage_id'])) ? ( ( (int) $data['to_storage_id'] > 0 ) ? true : false) : false;


        $validator 	= $this->validator->make($data, $rules);
        $properties = array();
        if(isset($data['properties']) && count($data['properties']))
        {
            $properties = $data['properties'];
            unset($data['properties']);
        }
        $rulesProps = array();
        $event_id = (isset($data['storage_events_id'])) ? intval($data['storage_events_id']) : 0;
        $event = ($event_id > 0) ? StorageEvents::findOrFail($event_id) : null;

        if($event)
        {
            if($event->properties)
            {
                foreach($event->properties as $prop)
                {
                    $bIsRequired = (bool) $prop->property->is_required;
                    if($bIsRequired)
                        $rulesProps[$prop->property->id] = 'required';
                }
            }
            $validatorProps = $this->validator->make($properties, $rulesProps);
        }
        else
        {
            return $this->redirect->back();
        }

        $isPass = false;
        $sendValidator = $validator->messages()->toArray();
        if(isset($validatorProps))
        {
            $sendValidator = $validatorProps->messages()->toArray() + $validator->messages()->toArray();

            if($validatorProps->passes() && $validator->passes())
                $isPass = true;
        }
        else
        {
            if($validator->passes())
                $isPass = true;
        }

        if(!$isPass)
            return $this->redirect->back()->withErrors($sendValidator)->withInput();
        else
        {
            $data['data'] = serialize($properties);

            $boolIsAriphmetic = (boolean) $event->is_arifmetic;

            $material = StorageHasMaterial::with(array('materials'))->where(array('storage_id' => $data['storage_id'], 'material_id' => $data['material_id']))->first();
            $total = intval($material->total);

            if($boolIsAriphmetic)
            {
                switch($event->char)
                {
                    case "+":
                        $total = $total + (int) $data['value'];
                        break;
                    case "-":
                        $total = $total - (int) $data['value'];
                        break;
                    case "~":
                        $total = $total - (int) $data['value'];
                        break;
                }


                $this->checkMaterials($material, $total);
                if($boolIStransfer)
                {
                    $from_storage = Storages::find($data['storage_id']);
                    $to_storage = Storages::find($data['to_storage_id']);
                    if($from_storage != null && $to_storage != null)
                    {
                        $arrData = array_merge($properties, array("from_storage" => array('id' => $from_storage->id, 'name' => $from_storage->name), "to_storage" => array('id' => $to_storage->id, 'name' => $to_storage->name)));
                        $data['data'] = serialize($arrData);

                        $materialTO = StorageHasMaterial::firstOrCreate(array('storage_id' => $to_storage->id, 'material_id' => $data['material_id']));
                        $totalTO = intval($materialTO->total);
                        $totalTO = $totalTO + (int) $data['value'];
                        $material->update(array('total' => $total));
                        $materialTO->update(array('total' => $totalTO));
                        $dataForNEWoperation = $data;
                        $dataForNEWoperation['storage_id'] = $to_storage->id;
                        $storage_event_to = $this->operations->create($dataForNEWoperation);
                        StorageHasEventsMaterials::create(array('storage_to_material_id' => $materialTO->id, 'storage_events_materials_id' => $storage_event_to->id, 'total_value' => $totalTO));
                    }
                    else
                        return $this->redirect->back()->withInput();

                }
                else
                    $material->update(array('total' => $total));
            }



            $storage_event = $this->operations->create($data);
            if($storage_event && $material)
            {
                StorageHasEventsMaterials::create(array('storage_to_material_id' => $material->id, 'storage_events_materials_id' => $storage_event->id, 'total_value' => $total));
            }
            return $this->redirect('operations.index');
        }

	}

    /**
     * Check quantity of material
     *
     * @param  object  $material
     * @param  int  $total
     * @return void
     */
    private function checkMaterials($material = null, $total = 0)
    {
        $materialConfig = Config::get('app.materialsConfig');
        $reults = [];
        $stotal = intval($material->total);
        $bDisabled = ($stotal > 0 && $total <= 0) ? true : false;
        $bEnabled = ($stotal <= 0 && $total > 0) ? true : false;
        //Если это бумага или материал
        if(in_array($material->materials->material_group_id, $materialConfig['group_ids']) && (int) $material->materials->is_disabled == 0)
        {
            if(array_key_exists($material->material_id, $materialConfig['materials']) && ($bDisabled || $bEnabled ))
            {
                $arrParams = $materialConfig['materials'][$material->material_id];
                $name = array_shift($arrParams);
                $k = 0;
                if(count($arrParams) > 0)
                {
                    foreach($arrParams as $site => $value)
                    {
                        $k++;
                        $data = ['material_id' => $value, 'enabled' => $bEnabled];
                        $url = "http://".$materialConfig['links'][$site];

                        $data_string = json_encode($data);
                        $result = file_get_contents($url, false, stream_context_create(array(
                            'http' => array(
                                'method' => 'POST',
                                'header' => 'Content-Type: application/json' . "\r\n"
                                    . 'Content-Length: ' . strlen($data_string) . "\r\n",
                                'content' => $data_string,
                            ),
                        )));

                        if($k == 1)
                        {
                            $arrResp = json_decode(trim(str_replace("(", "", str_replace(")", "", $result))), true);
                            if(isset($arrResp['value']['data']) && $arrResp['value']['data'] == "ok")
                                $reults[] = $arrResp['value']['data'];
                        }
                        else
                        {
                            $arrResp = json_decode($result,1);
                            if(isset($arrResp['status']['code']) && $arrResp['status']['code'] == "ok")
                                $reults[] = $arrResp['status']['code'];
                        }

                    }
                }
            }
        }
        return true;
    }


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


}

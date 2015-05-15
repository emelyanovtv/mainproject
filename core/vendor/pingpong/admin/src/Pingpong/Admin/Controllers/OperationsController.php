<?php
namespace Pingpong\Admin\Controllers;


use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
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
        $material_id = intval($material_id);
        $dateStr = strval($date);

        if(!strlen($dateStr))
            $dateStr = date('Y-m');


        //first case if we dont have not storage and material
        $storages = Storages::all();
        $storages_list = array('all' => "Все") + $storages->lists("name", "id");
        $materials_storage = array();

        foreach($storages as $storage)
        {
            if($storage->hasMaterials)
            {
                foreach($storage->hasMaterials as $data)
                {
                    $materials_storage[$storage->id][null] = "Нет";
                    $materials_storage[$storage->id][$data->materials->id] = $data->materials->name;
                }
            }
        }


        $dateArr = explode("-", $dateStr);
        $dayInMonth = cal_days_in_month(CAL_GREGORIAN, $dateArr[1], $dateArr[0]);
        $storagesArrData = array();
        $dataPlus = array();
        $list = Storages::with(array('hasMaterials.materials.materialsgroup' => function($query) {
                $query->orderBy('material_groups.id', 'ASC');
            }))->get();
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
                        $eventsForProduct = $material->events()->where('created_at', '>=', $dateStr."-01")->where('created_at', '<=', $dateStr."-".$dayInMonth)->get();

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




	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        $storages = Storages::all();
        $events = StorageEvents::all();
        $storages_list = array("NULL" => "Нет") + $storages->lists('name', 'id');
        $events_list = array("NULL" => "Нет") + $events->lists('name', 'id');
        $materials_storage = array();
        $events_props = array();
        $user_id = Auth::user()->id;

        foreach($events as $event)
        {
            if($event->properties)
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

            $material = StorageHasMaterial::where(array('storage_id' => $data['storage_id'], 'material_id' => $data['material_id']))->first();
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

<?php namespace Pingpong\Admin\Controllers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Pingpong\Admin\Entities\Materials;
use Pingpong\Admin\Entities\MaterialGroup;
use Pingpong\Admin\Entities\MaterialHasProperties;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Pingpong\Admin\Entities\StorageHasMaterial;
use Pingpong\Admin\Entities\Storages;

class ProductController extends BaseController {

    protected $materials;

    public function __construct(Materials $material)
    {
        $this->materials = $material;
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

    public function showall()
    {
        $materials = $this->materials->paginate(10);

        $no = $materials->getFrom();
        return $this->view('products.index', compact('materials', 'no'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    public function index()
	{
        $materials = $this->materials->paginate(10);

        $no = $materials->getFrom();
        return $this->view('products.index', compact('materials', 'no'));
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        $materialsGroupsData = MaterialGroup::with('properties', 'properties.property', 'properties.property.measure')->get();
        $material_groups = array("NULL" => "Нет") + $materialsGroupsData->lists("name", "id");
        $storages = Storages::all()->lists( "name", "id" );
        return $this->view('products.create', compact('material_groups', 'materialsGroupsData','storages'));
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
        $data 		= $this->inputAll();
        $rules      = $this->materials->getRules();
        $validator 	= $this->validator->make($data, $rules);
        $storages_id = ((isset($data['storage_id'])) && count($data['storage_id'])) ? $data['storage_id'] : null;
        unset($data['storage_id']);

        $properties = array();
        if(isset($data['property']) && count($data['property']))
        {
            $properties = (is_array($data['property'])) ? $data['property'] : [];
            unset($data['property']);
        }
        if ($validator->fails())
        {
            return $this->redirect->back()->withErrors($validator)->withInput();
        }


        if((int) $data['material_group_id'] > 0)
        {
            $rulesProps = array();
            $material = MaterialGroup::find($data['material_group_id']);
            if($material)
            {
                foreach($material->properties as $prop)
                {
                    $bIsRequired = (bool) $prop->property->is_required;
                    if($bIsRequired)
                        $rulesProps[$prop->id] = 'required';
                }

                $validatorProps = $this->validator->make($properties, $rulesProps);
                if ($validatorProps->fails())
                {
                    return $this->redirect->back()->withErrors($validatorProps)->withInput();
                }
            }

        }

        $materialCreated = $this->materials->create($data);



        if($materialCreated)
        {
            if(is_array($storages_id))
                foreach($storages_id as $num => $id)
                    StorageHasMaterial::firstOrCreate(array('storage_id' => $id, 'material_id' => $materialCreated->id));
            if(count($properties))
                foreach($properties as $id => $value)
                    MaterialHasProperties::create(array("material_id" => $materialCreated->id, "properties_id" => $id, "value" => $value));
        }

        return $this->redirect('product.index');
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
        try
        {
            $material = Materials::with(
                'materialsgroup.properties.property.measure',
                'values'
            )->find($id);
            $materialsGroupsData = MaterialGroup::with('properties', 'properties.property', 'properties.property.measure')->get();
            $material_groups = array("NULL" => "Нет") + $materialsGroupsData->lists('name', 'id');

            $storages =  Storages::all()->lists( "name", "id" );

            $values = $valuesStorage = array();

            if(count($material->hasStorage))
            {
                foreach($material->hasStorage as $data)
                {
                    $valuesStorage[] = $data->storage->id;
                }
            }

            if(count($material->values))
                foreach($material->values as $num => $var)
                    $values[$var->properties_id] = $var->value;
            return $this->view('products.edit', compact('material_groups', 'material','materialsGroupsData','values','storages','valuesStorage'));
        }
        catch(ModelNotFoundException $e)
        {
            return $this->redirectNotFound();
        }
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
        try
        {
            $data 		=	$this->inputAll();
            $material = $old_material =	$this->materials->findOrFail($id);
            $material_group_id = $material->material_group_id;
            $rules		=   $this->materials->getUpdateRules();
            $validator  = $this->validator->make($data, $rules);
            $storages_id = ((isset($data['storage_id'])) && count($data['storage_id'])) ? $data['storage_id'] : null;
            unset($data['storage_id']);

            if ($validator->fails())
            {
                return $this->redirect->back()->withErrors($validator)->withInput();
            }

            $properties = array();
            if(isset($data['property']) && count($data['property']))
            {
                $properties = (is_array($data['property'])) ? $data['property'] : [];
                unset($data['property']);
            }
            $data['is_disabled'] = (isset($data['is_disabled'])) ? intval($data['is_disabled']) : 0;

            if((int) $material->is_disabled != $data['is_disabled'])
                $this->checkMaterials($material, ($data['is_disabled'] == 0) ? true : false);

            $material->update($data);

            $rulesProps = array();
            foreach($material->materialsgroup->properties as $prop)
            {
                $bIsRequired = (bool) $prop->property->is_required;
                if($bIsRequired)
                    $rulesProps[$prop->id] = 'required';
            }

            $validatorProps = $this->validator->make($properties, $rulesProps);
            if ($validatorProps->fails())
            {
                return $this->redirect->back()->withErrors($validatorProps)->withInput();
            }

            /*
             * if change storage
             */
            if(is_array($storages_id))
            {
                $deleteStorageMaterials = [];
                if($material->hasStorage)
                {
                    foreach($material->hasStorage as $data)
                    {
                        $deleteStorageMaterials[] = $data->storage->id;
                    }
                }

                $result = array_diff($deleteStorageMaterials,$storages_id);
                if(count($result))
                {
                    foreach($result as $num => $id)
                        DB::table('storage_has_materials')->where('material_id', '=', $material->id)->where('storage_id', '=', $id)->delete();
                }
                foreach($storages_id as $num => $id)
                    StorageHasMaterial::firstOrCreate(array('storage_id' => $id, 'material_id' => $material->id));
            }


            /*
             *if change material group
             *then we delete all properties
             */

            if((int) $data['material_group_id'] != $material_group_id)
            {
                $del = DB::table('material_has_properties')->where('material_id', '=', $material->id)->delete();
                if($material)
                {
                    if(count($properties))
                        foreach($properties as $id => $value)
                            MaterialHasProperties::create(array("material_id" => $material->id, "properties_id" => $id, "value" => $value));
                }
            }
            else
            {
                if(count($properties))
                    foreach($properties as $id => $value)
                    {
                        $propsValues = MaterialHasProperties::where(array('material_id' => $material->id, 'properties_id' => $id));
                        if(count($propsValues->get()) != 0)
                            $propsValues->update(array('value' => $value));
                        else
                            MaterialHasProperties::create(array("material_id" => $material->id, "properties_id" => $id, "value" => $value));
                    }
            }

            return $this->redirect('product.index');
        }
        catch(ModelNotFoundException $e)
        {
            return $this->redirectNotFound();
        }
	}

    /**
     * Check quantity of material
     *
     * @param  object  $material
     * @param  int  $total
     * @return void
     */
    private function checkMaterials($material = null, $bEnabled = false)
    {
        $materialConfig = Config::get('app.materialsConfig');
        $reults = [];
        //Если это бумага или материал
        if(in_array($material->material_group_id, $materialConfig['group_ids']))
        {
            if(array_key_exists($material->id, $materialConfig['materials']))
            {
                $arrParams = $materialConfig['materials'][$material->id];
                $name = array_shift($arrParams);
                $k = 0;
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
        return true;
    }


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        try
        {
            Materials::destroy($id);

            return $this->redirect('product.index');
        }
        catch(ModelNotFoundException $e)
        {
            return $this->redirectNotFound();
        }
	}


}

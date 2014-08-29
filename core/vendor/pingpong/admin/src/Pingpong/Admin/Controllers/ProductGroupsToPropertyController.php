<?php namespace Pingpong\Admin\Controllers;

use Illuminate\Support\Facades\Input;
use Pingpong\Admin\Entities\MaterialGroupHasProperties;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductGroupsToPropertyController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{

	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{

	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
        $input = Input::json()->all();
        if(count($input))
        {
           foreach($input as $name => $val)
               ${$name} = $val;

            $saveArr = array("material_group_id" => $material_group_id, "properties_id" => $properties_id);


            if($material_group_id > 0)
            {
                $entety = MaterialGroupHasProperties::firstOrCreate($saveArr);
                print $entety->toJson();
            }
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
		//echo "<pre>";var_dump($id);echo "</pre>";die();
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{

	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
        $input = Input::json()->all();
        $entety = MaterialGroupHasProperties::find($input['id']);
        if($entety)
        {
            $entety->material_group_id = $input['material_group_id'];
            $entety->properties_id = $input['properties_id'];
            $entety->save();
            return $entety->toJson();
        }
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        MaterialGroupHasProperties::find($id)->delete();
	}


}

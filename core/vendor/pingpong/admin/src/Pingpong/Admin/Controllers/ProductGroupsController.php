<?php namespace Pingpong\Admin\Controllers;

use Pingpong\Admin\Entities\MaterialGroup;
use Pingpong\Admin\Entities\MaterialGroupHasProperties;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Pingpong\Admin\Entities\Properties;

class ProductGroupsController extends BaseController {


    protected $materialGroups;
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

    public function __construct(MaterialGroup $materialGroups)
    {
        $this->materialGroups = $materialGroups;
    }

	public function index()
	{

        $groups = $this->materialGroups->paginate(10);
        $no = $groups->getFrom();

        return $this->view('productgroups.index', compact('groups', 'no'));
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        $groups = array("NULL" => "Нет") + $this->materialGroups->lists('name', 'id');
        return $this->view('productgroups.create',  array('groups' => $groups));
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
        $data 		= $this->inputAll();
        $rules      = $this->materialGroups->getRules();
        $validator 	= $this->validator->make($data, $rules);

        $properties = array();
        unset($data['material_group_id']);
        if(isset($data['properties_id']) && count($data['properties_id']))
        {
            $properties = (is_array($data['properties_id'])) ? $data['properties_id'] : [];
            unset($data['properties_id']);
        }
        if ($validator->fails())
        {
            return $this->redirect->back()->withErrors($validator)->withInput();
        }

        if($data['parent_id'] == "NULL")
            $data['parent_id'] = null;
        $groupCreated = $this->materialGroups->create($data);

        if($groupCreated)
        {
            if(count($properties))
                foreach($properties as $num => $id)
                    MaterialGroupHasProperties::create(array("material_group_id" => $groupCreated->id, "properties_id" => $id));
        }

        return $this->redirect('productgroups.index');
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

    public function getChildTree($group)
    {
        return $group->child;
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
            $group = MaterialGroup::findOrFail($id);
            $group->load('properties');

            $child = $parent = $group;
            $notAvaliableArray = [];
            while($child != null)
            {
                $notAvaliableArray[] = $child->id;
                $child = $this->getChildTree($child);
            }

            $result = array_unique($notAvaliableArray);

            $groupsData = $this->materialGroups->whereNotIn("id", $result);


            $groups = array("NULL" => "Нет") + $groupsData->lists('name', 'id');

            return $this->view('productgroups.edit', compact('group', 'groups'));
        }
        catch(ModelNotFoundException $e)
        {
            return $this->redirectNotFound();
        }
	}

    public function getProperties()
    {
        return Properties::all(array('id', 'name'))->toJson();
    }


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
        $data 		=	$this->inputAll();
        $materialGroup = 	$this->materialGroups->findOrFail($id);
        $rules		=   $this->materialGroups->getUpdateRules();
        unset($data['material_group_id'], $data['properties_id']);

        $validator  = $this->validator->make($data, $rules);
        if ($validator->fails())
        {
            return $this->redirect->back()->withErrors($validator)->withInput();
        }

        if($data['parent_id'] == "NULL")
            $data['parent_id'] = null;

        $materialGroup->update($data);

        return $this->redirect('productgroups.index');
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
            MaterialGroup::destroy($id);

            return $this->redirect('productgroups.index');
        }
        catch(ModelNotFoundException $e)
        {
            return $this->redirectNotFound();
        }
	}


}

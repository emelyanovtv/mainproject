<?php namespace Pingpong\Admin\Controllers;

use Pingpong\Admin\Entities\Storages;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class StorageController extends BaseController {

    protected $storages;

    public function __construct(Storages $storages)
    {
        $this->storages = $storages;
    }
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $storages = $this->storages->paginate(10);
        $no      = $storages->getFrom();
        return $this->view('storage.index', compact('storages', 'no'));
	}

    public function getstoragesnotin($id = 0)
    {
        $storages = $this->storages->whereNotIn('id', array($id))->get();
        $storagesReturnArray = array();
        if(count($storages))
            foreach($storages as $storage)
                $storagesReturnArray[] = array('id' => $storage->id, 'name' => $storage->name);

        return json_encode($storagesReturnArray);
    }


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{

        $storages = array("NULL" => "Нет") + $this->storages->lists('name', 'id');
        return $this->view('storage.create', compact('storages'));
	}

    public function getChildTree($storage)
    {
        return $storage->child;
    }


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
        $data 		= $this->inputAll();
        $rules      = $this->storages->getRules();
        $validator 	= $this->validator->make($data, $rules);

        if ($validator->fails())
        {
            return $this->redirect->back()->withErrors($validator)->withInput();
        }

        if($data['parent_id'] == "NULL")
            $data['parent_id'] = null;

        $this->storages->create($data);

        return $this->redirect('storage.index');
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
            $storage = $this->storages->findOrFail($id);

            $notAvaliableArray = [];

            $notAvaliableArray[] = $storage->id;
            if(count($storage->child))
            {
                foreach($storage->child as $child)
                {
                    $notAvaliableArray[] = $child->id;
                }
            }


            $result = array_unique($notAvaliableArray);

            $storagesData = $this->storages->whereNotIn("id", $result);


            $storages = array("NULL" => "Нет") + $storagesData->lists('name', 'id');

            return $this->view('storage.edit', compact('storage', 'storages'));
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
            $storage = 	$this->storages->findOrFail($id);
            $rules		=   $this->storages->getUpdateRules();

            $validator  = $this->validator->make($data, $rules);
            if ($validator->fails())
            {
                return $this->redirect->back()->withErrors($validator)->withInput();
            }

            if($data['parent_id'] == "NULL")
                $data['parent_id'] = null;

            $storage->update($data);

            return $this->redirect('storage.index');
        }
        catch(ModelNotFoundException $e)
        {
            return $this->redirectNotFound();
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
        try
        {
            Storages::destroy($id);

            return $this->redirect('storage.index');
        }
        catch(ModelNotFoundException $e)
        {
            return $this->redirectNotFound();
        }
	}


}

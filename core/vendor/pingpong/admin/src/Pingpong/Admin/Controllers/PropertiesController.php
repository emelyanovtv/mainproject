<?php namespace Pingpong\Admin\Controllers;


use Pingpong\Admin\Entities\Measures;
use Pingpong\Admin\Entities\Properties;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PropertiesController extends BaseController {

    protected $properties;

    public function __construct(Properties $prop)
    {
        $this->properties = $prop;
    }
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $this->properties->load('measure');
		$properties = $this->properties->paginate(10);
        $no      = $properties->getFrom();

        return $this->view('properties.index', compact('properties', 'no'));
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        $measures = Measures::all()->lists("name", "id");
        return $this->view('properties.create', compact('measures'));
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
        $data 		= $this->inputAll();
        $rules      = $this->properties->getRules();
        $validator 	= $this->validator->make($data, $rules);

        if ($validator->fails())
        {
            return $this->redirect->back()->withErrors($validator)->withInput();
        }
        $this->properties->create($data);

        return $this->redirect('properties.index');
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
            $property = $this->properties->findOrFail($id);
            $measures = Measures::all()->lists("name", "id");
            return $this->view('properties.edit', compact('property', 'measures'));
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
            $data  = $this->inputAll();
            $prop  = $this->properties->findOrFail($id);
            $rules =  $this->properties->getUpdateRules();
            $data['is_required'] = (isset($data['is_required'])) ? 1 : 0;
            $validator  = $this->validator->make($data, $rules);
            if ($validator->fails())
            {
                return $this->redirect->back()->withErrors($validator)->withInput();
            }

            $prop->update($data);

            return $this->redirect('properties.index');
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
            Properties::destroy($id);

            return $this->redirect('properties.index');
        }
        catch(ModelNotFoundException $e)
        {
            return $this->redirectNotFound();
        }
	}


}

<?php namespace Pingpong\Admin\Controllers;


use Pingpong\Admin\Entities\EventsProperties;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Pingpong\Admin\Entities\Measures;

class EventsPropertiesController extends BaseController {

    protected $props;

    public function __construct(EventsProperties $eventsProperties)
    {
        $this->props = $eventsProperties;
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $properties = $this->props->paginate(10);
        $no = $properties->getFrom();
        return $this->view('eventprops.index', compact('properties', 'no'));
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        $measures = Measures::all()->lists("name", "id");
        return $this->view('eventprops.create', compact('measures'));
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
        $data 		= $this->inputAll();
        $rules      = $this->props->getRules();
        $validator 	= $this->validator->make($data, $rules);
        if ($validator->fails())
        {
            return $this->redirect->back()->withErrors($validator)->withInput();
        }
        $this->props->create($data);

        return $this->redirect('eventprops.index');
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
            $property = $this->props->findOrFail($id);
            $measures = Measures::all()->lists("name", "id");
            return $this->view('eventprops.edit', compact('property', 'measures'));
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
            $prop  = $this->props->findOrFail($id);
            $rules =  $this->props->getUpdateRules();
            $data['is_required'] = (isset($data['is_required'])) ? 1 : 0;
            $validator  = $this->validator->make($data, $rules);
            if ($validator->fails())
            {
                return $this->redirect->back()->withErrors($validator)->withInput();
            }

            $prop->update($data);

            return $this->redirect('eventprops.index');
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
            EventsProperties::destroy($id);
            return $this->redirect('eventprops.index');
        }
        catch(ModelNotFoundException $e)
        {
            return $this->redirectNotFound();
        }
	}


}

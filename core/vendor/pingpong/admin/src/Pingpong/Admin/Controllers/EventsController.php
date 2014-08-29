<?php namespace Pingpong\Admin\Controllers;


use Pingpong\Admin\Entities\EventsProperties;
use Pingpong\Admin\Entities\StorageEvents;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Pingpong\Admin\Entities\StorageEventsHasEventsProperties;

class EventsController extends BaseController {

    protected $events;


    public function __construct(StorageEvents $storageEvents)
    {
        $this->events = $storageEvents;
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $events = $this->events->paginate(10);
        $no = $events->getFrom();
        return $this->view('events.index', compact('events', 'no'));
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        return $this->view('events.create');
	}

    public function getProperties()
    {
        return EventsProperties::all(array('id', 'name'))->toJson();
    }


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
        $data 		= $this->inputAll();
        $rules      = $this->events->getRules();


        $properties = array();
        unset($data['storage_events_id']);
        if(isset($data['properties_id']) && count($data['properties_id']))
        {
            $properties = (is_array($data['properties_id'])) ? $data['properties_id'] : [];
            unset($data['properties_id']);
        }

        $data['is_arifmetic'] = (isset($data['is_arifmetic'])) ? $data['is_arifmetic'] : 0;

        $validator 	= $this->validator->make($data, $rules);
        if ($validator->fails())
        {
            return $this->redirect->back()->withErrors($validator)->withInput();
        }

       $eventCreated = $this->events->create($data);
        if($eventCreated)
        {
            if(count($properties))
                foreach($properties as $num => $id)
                    StorageEventsHasEventsProperties::create(array("storage_events_id" => $eventCreated->id, "event_prop_id" => $id));
        }

        return $this->redirect('events.index');
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
            $event = StorageEvents::findOrFail($id);
            return $this->view('events.edit', compact('event'));
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
        $data 		=	$this->inputAll();
        $event = 	$this->events->findOrFail($id);
        $rules		=   $this->events->getUpdateRules();
        unset($data['storage_events_id'], $data['properties_id']);

        $validator  = $this->validator->make($data, $rules);
        if ($validator->fails())
        {
            return $this->redirect->back()->withErrors($validator)->withInput();
        }

        $data['is_arifmetic'] = (isset($data['is_arifmetic'])) ? (int) $data['is_arifmetic'] : 0;

        if($data['is_arifmetic'] == 0)
            $data['char'] = "";

        $event->update($data);

        return $this->redirect('events.index');
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
            StorageEvents::destroy($id);
            return $this->redirect('events.index');
        }
        catch(ModelNotFoundException $e)
        {
            return $this->redirectNotFound();
        }
	}


}

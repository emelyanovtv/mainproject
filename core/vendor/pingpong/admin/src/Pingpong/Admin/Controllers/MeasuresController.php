<?php
namespace Pingpong\Admin\Controllers;


use Pingpong\Admin\Entities\Measures;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MeasuresController extends BaseController {

    protected $measures;

    public function __construct(Measures $measures)
    {
        $this->measures = $measures;
    }
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $measure = $this->measures->paginate(10);
        $no      = $measure->getFrom();
        return $this->view('measures.index', compact('measure', 'no'));
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        return $this->view('measures.create');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
        $data 		= $this->inputAll();
        $rules      = $this->measures->getRules();
        $validator 	= $this->validator->make($data, $rules);

        if ($validator->fails())
        {
            return $this->redirect->back()->withErrors($validator)->withInput();
        }

        $this->measures->create($data);

        return $this->redirect('measures.index');
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
            $measure = Measures::findOrFail($id);
            return $this->view('measures.edit', compact('measure'));
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
            $measure = 	$this->measures->findOrFail($id);
            $rules		=   $this->measures->getUpdateRules();

            $validator  = $this->validator->make($data, $rules);
            if ($validator->fails())
            {
                return $this->redirect->back()->withErrors($validator)->withInput();
            }

            $measure->update($data);

            return $this->redirect('measures.index');
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
            Measures::destroy($id);

            return $this->redirect('measures.index');
        }
        catch(ModelNotFoundException $e)
        {
            return $this->redirectNotFound();
        }
	}


}

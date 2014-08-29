<?php namespace Pingpong\Admin\Controllers;

use Pingpong\Admin\Entities\Permission;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PermissionsController extends BaseController {
	
	/**
	 * @var \Permission
	 */
	protected $permissions;

	/**
	 * @param \Permission $permissions
	 */
	public function __construct(Permission $permissions)
	{
		$this->permissions = $permissions;
	}
	
	/**
	 * Redirect not found.
	 *
	 * @return Response
	 */
	protected function redirectNotFound()
	{
		return $this->redirect('permissions.index');
	}

	/**
	 * Display a listing of permissions
	 *
	 * @return Response
	 */
	public function index()
	{
		$permissions = $this->permissions->paginate(10);
		$no 		  = $permissions->getFrom();

		return $this->view('permissions.index', compact('permissions', 'no'));
	}

	/**
	 * Show the form for creating a new permission
	 *
	 * @return Response
	 */
	public function create()
	{
		return $this->view('permissions.create');
	}

	/**
	 * Store a newly created permission in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$data 		= $this->inputAll();
		$rules      = $this->permissions->getRules();
		$validator 	= $this->validator->make($data, $rules);

		if ($validator->fails())
		{
			return $this->redirect->back()->withErrors($validator)->withInput();
		}

		$this->permissions->create($data);

		return $this->redirect('permissions.index');
	}

	/**
	 * Display the specified permission.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		try
		{
			$permission = $this->permissions->findOrFail($id);
			return $this->view('permissions.show', compact('permission'));
		}
		catch(ModelNotFoundException $e)
		{
			return $this->redirectNotFound();
		}
	}

	/**
	 * Show the form for editing the specified permission.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{		
		try
		{
			$permission = $this->permissions->findOrFail($id);
			return $this->view('permissions.edit', compact('permission'));
		}		
		catch(ModelNotFoundException $e)
		{
			return $this->redirectNotFound();
		}
	}

	/**
	 * Update the specified permission in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		try
		{
			$data 		=	$this->inputAll();
			$permission = 	$this->permissions->findOrFail($id);
			$rules		=   $this->permissions->getUpdateRules();

			$validator  = $this->validator->make($data, $rules);

			if ($validator->fails())
			{
				return $this->redirect->back()->withErrors($validator)->withInput();
			}

			$permission->update($data);

			return $this->redirect('permissions.index');
		}
		catch(ModelNotFoundException $e)
		{
			return $this->redirectNotFound();
		}
	}

	/**
	 * Remove the specified permission from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		try
		{
			$this->permissions->destroy($id);

			return $this->redirect('permissions.index');
		}		
		catch(ModelNotFoundException $e)
		{
			return $this->redirectNotFound();
		}
	}

}

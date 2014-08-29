@extends('admin::layouts.master')

@section('content')
	
	<h4 class="page-header">
		Редактирование
		&middot;
		<small>{{ link_to_route('admin.storage.index', 'Назад') }}</small>
	</h4>

	<div>
		@include('admin::storage.form', array('model' => $storage))
	</div>

@stop
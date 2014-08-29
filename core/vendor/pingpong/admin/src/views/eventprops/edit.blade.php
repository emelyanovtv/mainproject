@extends('admin::layouts.master')

@section('content')
	
	<h4 class="page-header">
		Редактирование
		&middot;
		<small>{{ link_to_route('admin.eventprops.index', 'Назад') }}</small>
	</h4>

	<div>
		@include('admin::eventprops.form', array('model' => $property, 'measures' => $measures))
	</div>

@stop
@extends('admin::layouts.master')

@section('content')
	<h4 class="page-header">
		Редактирование
		&middot;
		<small>{{ link_to_route('admin.operations.index', 'Назад') }}</small>
	</h4>

	<div>
		@include('admin::operations.form')
	</div>

@stop
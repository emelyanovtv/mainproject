@extends('admin::layouts.master')

@section('content')
	
	<h4 class="page-header">
		Добавление
		&middot;
		<small>{{ link_to_route('admin.properties.index', 'Назад') }}</small>
	</h4>

	<div>
		@include('admin::properties.form')
	</div>

@stop
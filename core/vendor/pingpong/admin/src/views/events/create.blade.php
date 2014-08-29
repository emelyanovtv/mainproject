@extends('admin::layouts.master')

@section('content')
	
	<h4 class="page-header">
		Добавление
		&middot;
		<small>{{ link_to_route('admin.events.index', 'Назад') }}</small>
	</h4>

	<div>
		@include('admin::events.form')
	</div>

@stop
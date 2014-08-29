@extends('admin::layouts.master')

@section('content')
	
	<h4 class="page-header">
		Добавление
		&middot;
		<small>{{ link_to_route('admin.storage.index', 'Назад') }}</small>
	</h4>

	<div>
		@include('admin::storage.form')
	</div>

@stop
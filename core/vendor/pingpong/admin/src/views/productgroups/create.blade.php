@extends('admin::layouts.master')

@section('content')
	
	<h4 class="page-header">
		Добавление
		&middot;
		<small>{{ link_to_route('admin.productgroups.index', 'Назад') }}</small>
	</h4>

	<div>
		@include('admin::productgroups.form')
	</div>

@stop
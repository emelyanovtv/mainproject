@extends('admin::layouts.master')

@section('content')
	
	<h4 class="page-header">
		Редактирование
		&middot;
		<small>{{ link_to_route('admin.measures.index', 'Назад') }}</small>
	</h4>

	<div>
		@include('admin::measures.form', array('model' => $measure))
	</div>

@stop
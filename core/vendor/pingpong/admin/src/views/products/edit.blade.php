@extends('admin::layouts.master')

@section('content')

	<h4 class="page-header">
		Редактирование
		&middot;
		<small>{{ link_to_route('admin.product.index', 'Назад') }}</small>
	</h4>

	<div>
		@include('admin::products.form', array('model' => $material))
	</div>

@stop
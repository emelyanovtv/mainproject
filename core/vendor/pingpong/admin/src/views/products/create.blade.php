@extends('admin::layouts.master')

@section('content')
    <script type="text/javascript">
        var block_id_html = "";

    </script>
	<h4 class="page-header">
		Добавление
		&middot;
		<small>{{ link_to_route('admin.product.index', 'Назад') }}</small>
	</h4>

	<div>
		@include('admin::products.form')
	</div>

@stop
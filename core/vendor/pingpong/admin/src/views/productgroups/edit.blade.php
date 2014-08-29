@extends('admin::layouts.master')

@section('content')

<h4 class="page-header">
    Редактирование
    &middot;
    <small>{{ link_to_route('admin.productgroups.index', 'Назад') }}</small>
</h4>

<div>
    @include('admin::productgroups.form', array('model' => $group, 'groups' => $groups))
</div>

@stop
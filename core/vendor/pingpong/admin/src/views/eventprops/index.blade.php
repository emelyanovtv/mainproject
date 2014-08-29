@extends('admin::layouts.master')

@section('content')

<h4 class="page-header">
    Все размерности({{ $properties->getTotal() }})
    &middot;
    <small>{{ link_to_route('admin.eventprops.create', 'Добавить новую') }}</small>
</h4>

<table class="table">
    <thead>
    <th>ID</th>
    <th>Наименование</th>
    <th>Размерность</th>
    <th>Обязательное</th>
    <th>Создано</th>
    <th class="text-center">Действия</th>
    </thead>
    <tbody>
    @foreach ($properties as $prop)
    <tr>
        <td>{{ $no }}</td>
        <td>{{ $prop->name }}</td>
        <td>{{ $prop->measure->name }}</td>
        <td>{{ ($prop->is_required) ? 'Да' : 'Нет' }}</td>
        <td>{{ $prop->created_at }}</td>
        <td class="text-center">
            <a href="{{ route('admin.eventprops.edit', $prop->id) }}">Редактировать</a>
            &middot;
            @include('admin::partials.modal', ['data' => $prop, 'name' => 'eventprops'])
        </td>
    </tr>
    <?php $no++ ;?>
    @endforeach
    </tbody>
</table>


<div class="text-center">
    {{ pagination_links($properties) }}
</div>
@stop
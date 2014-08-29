@extends('admin::layouts.master')

@section('content')

<h4 class="page-header">
    Все Склады({{ $storages->getTotal() }})
    &middot;
    <small>{{ link_to_route('admin.storage.create', 'Добавить новую') }}</small>
</h4>

<table class="table">
    <thead>
    <th>ID</th>
    <th>Наименование</th>
    <th>Родительский склад</th>
    <th>Создано</th>
    <th class="text-center">Действия</th>
    </thead>
    <tbody>
    @foreach ($storages as $storage)
    <tr>
        <td>{{ $no }}</td>
        <td>{{ $storage->name }}</td>
        <td>{{ ($storage->parent ==  null) ? "Нет" : $storage->parent->name}}</td>
        <td>{{ $storage->created_at }}</td>
        <td class="text-center">
            <a href="{{ route('admin.storage.edit', $storage->id) }}">Редактировать</a>
            &middot;
            @include('admin::partials.modal', ['data' => $storage, 'name' => 'storage'])
        </td>
    </tr>
    <?php $no++ ;?>
    @endforeach
    </tbody>
</table>


<div class="text-center">
    {{ pagination_links($storages) }}
</div>
@stop
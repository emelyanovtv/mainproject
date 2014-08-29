@extends('admin::layouts.master')

@section('content')

<h4 class="page-header">
    Все размерности({{ $groups->getTotal() }})
    &middot;
    <small>{{ link_to_route('admin.productgroups.create', 'Добавить новую') }}</small>
</h4>

<table class="table">
    <thead>
    <th>No</th>
    <th>Наименование</th>
    <th>Родительская группа</th>
    <th>Создано</th>
    <th class="text-center">Действия</th>
    </thead>
    <tbody>
    @foreach ($groups as $group)
    <tr>
        <td>{{ $no }}</td>
        <td>{{ $group->name }}</td>
        <td>{{ ($group->parent_id == null) ? "Нет" : $group->parent->name }}</td>
        <td>{{ $group->created_at }}</td>
        <td class="text-center">
            <a href="{{ route('admin.productgroups.edit', $group->id) }}">Редактировать</a>
            &middot;
            @include('admin::partials.modal', ['data' => $group, 'name' => 'productgroups'])
        </td>
    </tr>
    <?php $no++ ;?>
    @endforeach
    </tbody>
</table>


<div class="text-center">
    {{ pagination_links($groups) }}
</div>
@stop
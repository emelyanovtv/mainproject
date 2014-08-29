@extends('admin::layouts.master')

@section('content')

<h4 class="page-header">
    Продукты ({{ $materials->getTotal() }})
    &middot;
    <small>{{ link_to_route('admin.product.create', 'Добавить новый') }}</small>
</h4>

<table class="table">
    <thead>
    <th>ID</th>
    <th>Наименование</th>
    <th>Группа</th>
    <th>Создано</th>
    <th class="text-center">Действия</th>
    </thead>
    <tbody>
    @foreach ($materials as $material)
    <tr>
        <td>{{ $no }}</td>
        <td>{{ $material->name }}</td>
        <td>{{ $material->materialsgroup->name }}</td>
        <td>{{ $material->created_at }}</td>
        <td class="text-center">
            <a href="{{ route('admin.product.edit', $material->id) }}">Редактировать</a>
            &middot;
            @include('admin::partials.modal', ['data' => $material, 'name' => 'product'])
        </td>
    </tr>
    <?php $no++ ;?>
    @endforeach
    </tbody>
</table>


<div class="text-center">
    {{ pagination_links($materials) }}
</div>
@stop
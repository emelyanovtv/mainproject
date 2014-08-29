@extends('admin::layouts.master')

@section('content')

<h4 class="page-header">
    Все размерности({{ $measure->getTotal() }})
    &middot;
    <small>{{ link_to_route('admin.measures.create', 'Добавить новую') }}</small>
</h4>

<table class="table">
    <thead>
    <th>ID</th>
    <th>Наименование</th>
    <th>Код</th>
    <th>Создано</th>
    <th class="text-center">Действия</th>
    </thead>
    <tbody>
    @foreach ($measure as $mes)
    <tr>
        <td>{{ $no }}</td>
        <td>{{ $mes->name }}</td>
        <td>{{ $mes->code }}</td>
        <td>{{ $mes->created_at }}</td>
        <td class="text-center">
            <a href="{{ route('admin.measures.edit', $mes->id) }}">Редактировать</a>
            &middot;
            @include('admin::partials.modal', ['data' => $mes, 'name' => 'measures'])
        </td>
    </tr>
    <?php $no++ ;?>
    @endforeach
    </tbody>
</table>


<div class="text-center">
    {{ pagination_links($measure) }}
</div>
@stop
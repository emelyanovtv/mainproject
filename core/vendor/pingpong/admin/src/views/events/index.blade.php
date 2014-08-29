@extends('admin::layouts.master')

@section('content')

<h4 class="page-header">
    Все размерности({{ $events->getTotal() }})
    &middot;
    <small>{{ link_to_route('admin.events.create', 'Добавить новую') }}</small>
</h4>

<table class="table">
    <thead>
    <th>No</th>
    <th>Наименование</th>
    <th>Создано</th>
    <th class="text-center">Действия</th>
    </thead>
    <tbody>
    @foreach ($events as $event)
    <tr>
        <td>{{ $no }}</td>
        <td>{{ $event->name }}</td>
        <td>{{ $event->created_at }}</td>
        <td class="text-center">
            <a href="{{ route('admin.events.edit', $event->id) }}">Редактировать</a>
            &middot;
            @include('admin::partials.modal', ['data' => $event, 'name' => 'events'])
        </td>
    </tr>
    <?php $no++ ;?>
    @endforeach
    </tbody>
</table>


<div class="text-center">
    {{ pagination_links($events) }}
</div>
@stop
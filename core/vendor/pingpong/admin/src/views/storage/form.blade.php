@if(isset($model))
{{ Form::model($model, ['method' => 'PUT', 'files' => true, 'route' => ['admin.storage.update', $model->id]]) }}
@else
{{ Form::open(['files' => true, 'route' => 'admin.storage.store']) }}
@endif
	<div class="form-group">
		{{ Form::label('name', 'Название склада:') }}
		{{ Form::text('name', null, ['class' => 'form-control']) }}
		{{ $errors->first('name', '<div class="text-danger">:message</div>') }}
	</div>
<div class="form-group">
    {{ Form::label('parent_id', 'Родительская группа:') }}
    {{ Form::select('parent_id', $storages, null, ['class' => 'form-control']) }}
    {{ $errors->first('parent_id', '<div class="text-danger">:message</div>') }}
</div>
	<div class="form-group">
		{{ Form::submit(isset($model) ? 'Обновить' : 'Сохранить', ['class' => 'btn btn-primary']) }}
	</div>
{{ Form::close() }}
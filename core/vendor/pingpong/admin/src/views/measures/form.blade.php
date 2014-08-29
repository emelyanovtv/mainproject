@if(isset($model))
{{ Form::model($model, ['method' => 'PUT', 'files' => true, 'route' => ['admin.measures.update', $model->id]]) }}
@else
{{ Form::open(['files' => true, 'route' => 'admin.measures.store']) }}
@endif
	<div class="form-group">
		{{ Form::label('name', 'Название:') }}
		{{ Form::text('name', null, ['class' => 'form-control']) }}
		{{ $errors->first('name', '<div class="text-danger">:message</div>') }}
	</div>
<div class="form-group">
    {{ Form::label('code', 'Символьный код:') }}
    {{ Form::text('code', null, ['class' => 'form-control']) }}
    {{ $errors->first('code', '<div class="text-danger">:message</div>') }}
</div>
	<div class="form-group">
		{{ Form::submit(isset($model) ? 'Обновить' : 'Сохранить', ['class' => 'btn btn-primary']) }}
	</div>
{{ Form::close() }}
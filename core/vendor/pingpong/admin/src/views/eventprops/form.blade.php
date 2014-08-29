@if(isset($model))
{{ Form::model($model, ['method' => 'PUT', 'files' => true, 'route' => ['admin.eventprops.update', $model->id]]) }}
@else
{{ Form::open(['files' => true, 'route' => 'admin.eventprops.store']) }}
@endif
	<div class="form-group">
		{{ Form::label('name', 'Название:') }}
		{{ Form::text('name', null, ['class' => 'form-control']) }}
		{{ $errors->first('name', '<div class="text-danger">:message</div>') }}
	</div>
<div class="form-group">
    {{ Form::label('measures_id', 'Размерность:') }}
    {{ Form::select('measures_id', $measures, isset($measure) ? $measure : null, ['class' => 'form-control']) }}
    {{ $errors->first('measures_id', '<div class="text-danger">:message</div>') }}
</div>
<div class="form-group">
    {{ Form::label('is_required', 'Обязательное?:') }}
    {{ Form::checkbox('is_required', '1', false, ['class' => 'checkbox']) }}
    {{ $errors->first('is_required', '<div class="text-danger">:message</div>') }}
</div>
	<div class="form-group">
		{{ Form::submit(isset($model) ? 'Обновить' : 'Сохранить', ['class' => 'btn btn-primary']) }}
	</div>
{{ Form::close() }}
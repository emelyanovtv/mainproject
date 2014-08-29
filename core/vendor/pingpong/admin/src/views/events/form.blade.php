@if(isset($model))
{{ Form::model($model, ['method' => 'PUT', 'files' => true, 'route' => ['admin.events.update', $model->id]]) }}
@else
{{ Form::open(['files' => true, 'route' => 'admin.events.store']) }}
@endif
	<div class="form-group">
		{{ Form::label('name', 'Название:') }}
		{{ Form::text('name', null, ['class' => 'form-control']) }}
		{{ $errors->first('name', '<div class="text-danger">:message</div>') }}
        {{ Form::hidden('storage_events_id', isset($model) ? $model->id : null, array('id' => 'storage_events_id_main')) }}
	</div>
<div class="form-group">
    {{ Form::label('description', 'Описание:') }}
    {{ Form::textarea('description', null, ['class' => 'form-control','size' => '30x5']) }}
    {{ $errors->first('description', '<div class="text-danger">:message</div>') }}
</div>

<div class="form-group">
    {{ Form::label('is_arifmetic', 'Содержит математические действия?:') }}
    {{ Form::checkbox('is_arifmetic', '1', false, ['class' => 'checkbox']) }}
    {{ $errors->first('is_arifmetic', '<div class="text-danger">:message</div>') }}
</div>

<div class="form-group showing" style="display: none;">
    {{ Form::label('char', 'Арифметическое действие') }}
    {{ Form::select('char', array('+' => '+', '-' => '-', '~' => 'Первод'), null, ['class' => 'form-control']) }}
    {{ $errors->first('char', '<div class="text-danger">:message</div>') }}
</div>

<div class="form-group">
    <div id="properties">

    </div>
</div>
    <div class="form-group">
        {{ Form::button('Добавить свойство +', ['class' => 'btn btn-inverse', 'id' => 'addProperty']) }}
    </div>
	<div class="form-group">
		{{ Form::submit(isset($model) ? 'Обновить' : 'Сохранить', ['class' => 'btn btn-primary']) }}
	</div>

{{ Form::close() }}
<script type="text/template" id="propertiesTemplate">
    <label for="property-selector">Свойство</label>
    <select class="property-selector" name="properties_id[<%= num %>]">

        <% _(properties).each(function(prop) { %>
            <option <% if (prop.id == properties_id) { %>selected<% }  %>  value="<%= prop.id %>"><%= prop.name %></option>
        <% }); %>
    </select>
    <input type="hidden"  name="storage_events_id" id="storage_events_id" value="<%= storage_events_id %>" />
        {{ Form::button('<%= button_name %>', ['class' => 'btn btn-success edit']) }}
    {{ Form::button('Удалить', ['class' => 'btn btn-warning delete']) }}
</script>
{{ script('js/event_properties.js') }}
<script type="text/javascript">

    var showFieldChar = function(obj)
    {
        var elem = $('div.form-group.showing');
        if(obj != null && obj != "undefined")
        {
            if(obj.is(':checked'))
                elem.show();
            else
                elem.hide();

        }
    }
    $(document).ready(function(){

            showFieldChar($('#is_arifmetic'));

            $('#is_arifmetic').on('click', function(){
                showFieldChar($(this));
            });

            $.ajax({
                url:"/admin/events/properties",
                type:"GET",
                success:function(result){
                    var tasks = [];
                    @if(isset($event) && count($event->properties))
                    @foreach ($event->properties as $props)
                            tasks.push({
                                id:{{$props->id}},
                                storage_events_id : {{$props->storage_events_id}},
                                properties_id : {{$props->event_prop_id}},
                                button_name:"Обновить"
                            });
                            App.Attributes.ExistProps.push({{$props->event_prop_id}});
                    @endforeach
                    @endif

                    var collectionProps = new App.Collections.Properties(tasks);
                    App.Attributes.PropertyList = JSON.parse(result);
                    App.Attributes.Properties = new App.Views.Properties({collection: collectionProps});
                    var AddPropertyView = new App.Views.AddProperty({collection:collectionProps});
                    var html = App.Attributes.Properties.render().$el;
                    $('#properties').html(html);
                 }
            });
    });
</script>
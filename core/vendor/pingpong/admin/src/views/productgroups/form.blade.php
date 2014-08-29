@if(isset($model))
{{ Form::model($model, ['method' => 'PUT', 'files' => true, 'route' => ['admin.productgroups.update', $model->id]]) }}
@else
{{ Form::open(['files' => true, 'route' => 'admin.productgroups.store']) }}
@endif
	<div class="form-group">
		{{ Form::label('name', 'Название:') }}
		{{ Form::text('name', null, ['class' => 'form-control']) }}
		{{ $errors->first('name', '<div class="text-danger">:message</div>') }}
        {{ Form::hidden('material_group_id', isset($model) ? $model->id : null, array('id' => 'material_group_id_main')) }}
	</div>
    <div class="form-group">
        {{ Form::label('parent_id', 'Родительская группа:') }}
        {{ Form::select('parent_id', $groups, isset($group) ? $group->parent_id : null, ['class' => 'form-control']) }}
        {{ $errors->first('parent_id', '<div class="text-danger">:message</div>') }}
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
    <input type="hidden"  name="material_group_id" id="material_group_id" value="<%= material_group_id %>" />
        {{ Form::button('<%= button_name %>', ['class' => 'btn btn-success edit']) }}
    {{ Form::button('Удалить', ['class' => 'btn btn-warning delete']) }}
</script>
{{ script('js/properties.js') }}
<script type="text/javascript">
    $(document).ready(function(){
            $.ajax({
                url:"/admin/productgroups/properties",
                type:"GET",
                success:function(result){
                    var tasks = [];
                    @if(isset($group) && count($group->properties))
                    @foreach ($group->properties as $props)
                            tasks.push({
                                id:{{$props->id}},
                                material_group_id : {{$props->material_group_id}},
                                properties_id : {{$props->properties_id}},
                                button_name:"Обновить"
                            });
                            App.Attributes.ExistProps.push({{$props->properties_id}});
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
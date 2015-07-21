
@if(isset($model))
{{ Form::model($model, ['method' => 'PUT', 'files' => true, 'route' => ['admin.product.update', $model->id]]) }}
@else
{{ Form::open(['files' => true, 'route' => 'admin.product.store']) }}

@endif
    <div class="form-group">
        {{ Form::label('storage_id', 'Скалад:') }}
        {{ Form::select('storage_id[]', $storages, isset($valuesStorage) ? $valuesStorage : null, ['class' => 'form-control', 'multiple']) }}
        {{ $errors->first('material_group_id', '<div class="text-danger">:message</div>') }}
    </div>
    <div class="form-group">
        {{ Form::label('material_group_id', 'Группа:') }}
        {{ Form::select('material_group_id', $material_groups, isset($material->materialsgroup) ? $material->materialsgroup->id : null, ['class' => 'form-control', 'onchange' => 'putHtmlByID($(this).val())']) }}
        {{ $errors->first('material_group_id', '<div class="text-danger">:message</div>') }}
    </div>
	<div class="form-group">
		{{ Form::label('name', 'Название:') }}
		{{ Form::text('name', null, ['class' => 'form-control']) }}
		{{ $errors->first('name', '<div class="text-danger">:message</div>') }}
	</div>
<div class="form-group">
    {{ Form::label('is_disabled', 'Выключен:') }}
    {{ Form::checkbox('is_disabled', '1', false, ['class' => 'checkbox']) }}
</div>
    <div id="properties">
        @if(isset($model))
            @if(isset($material->materialsgroup->properties) && count($material->materialsgroup->properties))
                    @foreach($material->materialsgroup->properties as $prop)
                        <div class="form-group {{ (isset($prop->property->is_required) && $prop->property->is_required == '1') ? 'required' : '' }}">
                            {{ Form::label('property['.$prop->id.']', $prop->property->name) }}
                            <div class='controls form-inline'>
                                {{ Form::text('property['.$prop->id.']', (isset($values) && isset($values[$prop->id])) ? $values[$prop->id] : '', ['class' => 'form-control']) }}
                                <span>{{$prop->property->measure->name}} ({{$prop->property->measure->code}})</span>
                            </div>
                            {{ $errors->first($prop->id, '<div class="text-danger">:message</div>') }}
                        </div>
                    @endforeach
            @endif
        @endif
    </div>

	<div class="form-group">
		{{ Form::submit(isset($model) ? 'Обновить' : 'Сохранить', ['class' => 'btn btn-primary']) }}
	</div>
{{ Form::close() }}
<script type="text/javascript">
    var props = [];
    @if(count($materialsGroupsData))
        @foreach($materialsGroupsData as $materialGroup)
    var MaterialProp = {
        id:"{{$materialGroup->id}}",
        name:"{{$materialGroup->name}}",
        properties:[]
    };
    @if(isset($materialGroup->properties) && count($materialGroup->properties))
        @foreach($materialGroup->properties as $prop)
    var property = {
        name:"{{$prop->property->name}}",
        measure:"{{$prop->property->measure->name}}",
        code:"{{$prop->property->measure->code}}",
        id:"{{$prop->id}}",
        error : "{{ $errors->first($prop->id, '<div class=\"text-danger\">:message</div>') }}",
        required:{{($prop->property->is_required == 1) ? 'true' : 'false'}},
        @if(isset($values) && count($values))
           value:"{{isset($values[$prop->id]) ? $values[$prop->id] : ''}}"
        @else
            value:""
        @endif
    }
    MaterialProp.properties.push(property);
    @endforeach
    @endif
    props.push(MaterialProp);
    @endforeach
    @endif;

    var htmlArray = [];

    if(props.length > 0)
    {
        for(ind in props)
        {
            var html = "";
            if(props[ind].properties.length)
            {

                for(num in props[ind].properties)
                {
                    var prop = props[ind].properties[num];
                    var className = "";
                    if(prop.required)
                        className = "required";
                    html +="<div class='form-group " + className + "'>";

                    html += "<label for=''>" + prop.name + "</label>";
                    html += "<div class='controls form-inline'>"
                    html += "<input type = 'text' class = 'form-control' name = 'property[" + prop.id + "]' value = '" + prop.value + "'/>"
                    html += "<span>" + prop.measure + " (" + prop.code + ")" + "</span>";
                    html += prop.error;
                    html += "</div>";
                    html += "</div>";
                }

            }
            htmlArray[props[ind].id] = html;
        }
    }

    var putHtmlByID = function(id)
    {
        var html = "";
        var material_group_id = parseInt(id);
        if(material_group_id > 0)
        {
            html = htmlArray[material_group_id];
            if(!html.length)
                alert('У данной группы материалов нет свойств!');
        }


        $('#properties').html(html);
    }

    jQuery(document).ready(function($){
        putHtmlByID($('#material_group_id').val());
    });
</script>
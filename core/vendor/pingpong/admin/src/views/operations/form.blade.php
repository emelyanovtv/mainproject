@if(isset($model))
{{ Form::model($model, ['method' => 'PUT', 'files' => true, 'route' => ['admin.operations.update', $model->id]]) }}
@else
{{ Form::open(['files' => true, 'route' => 'admin.operations.store']) }}
@endif
	<div class="form-group">
		{{ Form::label('storage_id', 'Скалад:') }}
		{{ Form::select('storage_id', $storages_list, null, ['class' => 'form-control', 'onchange' => 'getHtmlByTypeAndID("materials",$(this).val())']) }}
        {{ Form::hidden('user_id', $user_id) }}
		{{ $errors->first('storage_id', '<div class="text-danger">:message</div>') }}
	</div>
    <div id="materials" class="form-group">
    </div>
    <div class="data hide">
        <div class="form-group">
            {{ Form::label('storage_events_id', 'Событие:') }}
            {{ Form::select('storage_events_id', $events_list, null, ['class' => 'form-control', 'onchange' => 'getHtmlByTypeAndID("props", $(this).val())']) }}
            {{ $errors->first('storage_events_id', '<div class="text-danger">:message</div>') }}
        </div>
        <div id="properties"></div>
        <div class="form-group">
            {{ Form::label('value', 'Количество:') }}
            {{ Form::text('value', null, ['class' => 'form-control',]) }}
            {{ $errors->first('value', '<div class="text-danger">:message</div>') }}
        </div>

        <div class="form-group">
            {{ Form::submit(isset($model) ? 'Обновить' : 'Сохранить', ['class' => 'btn btn-primary']) }}
        </div>
    </div>
{{ Form::close() }}
<script type="text/javascript">
    var materials_in_storage = [];
    var events_properties = [];
    @if(count($materials_storage))
        @foreach($materials_storage as $storage_id => $materials)
            materials_in_storage[{{$storage_id}}] = {
                html : ""
            };

            @if(count($materials))
                var html = "";
                    html += '{{ Form::label('material_id', 'Материал:') }}';
                    html += '{{ Form::select('material_id', $materials, null, ['class' => 'form-control']) }}';
                    html += "{{ $errors->first('material_id', '<div class=\"text-danger\">:message</div>') }}";
                materials_in_storage[{{$storage_id}}].html = html;
            @endif;
        @endforeach;
    @endif;

    @if(count($new_events))
        @foreach($new_events as $event_id => $data)
            events_properties[{{$event_id}}] = {
                html : "",
                is_transfer : false
            };

            var htmlStr = "";

            @if(isset($new_events))
                @if($data["char"] == "~")
                    events_properties[{{$event_id}}].is_transfer = true;
                @endif;
            @endif;

            @if(isset($events_props[$event_id]))
                @if(count($events_props[$event_id]))

                    @foreach($events_props[$event_id] as $property)
                        htmlStr += '<div class="form-group {{($property->is_required == '1') ? 'required' : ''}}">';
                        htmlStr += '{{ Form::label('properties['.$property->id.']', $property->name) }}';
                        htmlStr += '<div class="controls form-inline">'
                        htmlStr += '{{ Form::text('properties['.$property->id.']', null, ['class' => 'form-control']) }}';
                        htmlStr += "<span>{{$property->measure->name}} ({{$property->measure->code}})</span>"
                            htmlStr += "{{ $errors->first($property->id, '<div class=\"text-danger\">:message</div>') }}";
                            htmlStr += '</div>';
                            htmlStr += '</div>';
                    @endforeach;
                @endif;
            @endif;
            events_properties[{{$event_id}}].html = htmlStr;
        @endforeach;
    @endif



    var getHtmlByTypeAndID = function(type, id)
    {
        var valueID = parseInt(id);
        var valueType = type;
        var html = "";
        var data = null;
        var obj = $('#materials');
        var boolIsMaterial = true;
        var boolJSONsend = false;
        if(valueType == 'materials')
        {
            getHtmlByTypeAndID('props',$('#storage_events_id').val());
            if(materials_in_storage.length > 0 && valueID > 0)
                data = materials_in_storage[valueID];
        }
        if(valueType == 'props')
        {
            obj = $('#properties');
            boolIsMaterial = false;
            if(events_properties.length > 0 && valueID > 0)
                data = events_properties[valueID];
        }

        if(data != undefined && data != null)
        {
            if(data.html != "undefined")
            {

                if(data.is_transfer == true && !boolIsMaterial)
                {
                    boolJSONsend = true;
                    $.getJSON( '/admin/storage/getstoragesnotin/' + $('#storage_id').val(), function( dataRet ) {
                        var addHtml = "";
                        if(dataRet != "undefined" && dataRet.length > 0)
                        {
                            addHtml += '<div id="to_storage" class="form-group">';
                            addHtml += '<label for="storage_events_id">На склад:</label>';
                            addHtml += '<select class="form-control" name="to_storage_id"> ';
                            $.each( dataRet, function( num, obj ) {

                                addHtml += '<option value="' + obj.id + '">' + obj.name + '</option>';
                            });
                            addHtml += '</select> ';
                            addHtml += '</div> ';
                        }

                        html = addHtml + data.html;
                        obj.html(html);
                    });
                }

                html = data.html;
            }

        }

        if(!boolJSONsend)
        {
            if(boolIsMaterial)
            {
                if(html.length > 0)
                    $('.data').removeClass('hide').addClass('show');
                else if(html.length == 0)
                    $('.data').removeClass('show').addClass('hide');
            }

            obj.html(html);
        }
    }

    jQuery(document).ready(function($){
        getHtmlByTypeAndID('materials',$('#storage_id').val());
        getHtmlByTypeAndID('props',$('#storage_events_id').val());
    });
</script>
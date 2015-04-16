@extends('admin::layouts.master')

@section('content')

<h2>Операции</h2>
{{ Form::open(['files' => true, 'method' => 'GET', 'route' => 'admin.operations.showoperations']) }}
<div class="form-group">
    {{ Form::label('storage_id', 'Склад:') }}
    {{ Form::select('storage_id', $storages_list, (isset($storage_id)) ? $storage_id : null, ['class' => 'form-control', 'onchange' => 'getHtmlByTypeAndID("materials", $(this).val())']) }}
    {{ $errors->first('storage_id', '<div class="text-danger">:message</div>') }}
</div>

<div id="materials" class="form-group"></div>
<div class="form-group">
    {{ Form::label('date', 'Месяц:') }}
    {{ Form::datetime("date", $dateStr) }}
</div>
<div class="form-group">
    {{ Form::submit('Показать', ['class' => 'btn btn-primary']) }}
</div>
{{ Form::close() }}

@if(isset($dayInMonth) && (isset($storagesArrData) && count($storagesArrData)))
<div class="dataTableFormStorage">
   @foreach($storagesArrData as $st_id => $storage)
    <h2>Cклад : <span class="error">{{$storage['data']['name']}}</span></h2>
    <table class="table table-bordered">
        <tr class="info">
            <td>Название продукта:</td>
            <td>Остаток на начало:</td>
            <td align="center" colspan="{{$dayInMonth}}">Приход</td>
            <td align="center" colspan="{{$dayInMonth}}">Расход</td>
            <td>Остаток на конец:</td>
        </tr>
        <tr>
            <td class="warning"></td>
            <td class="warning"></td>
            @for($i = 1; $i <= $dayInMonth; $i++)
                <td align="center" class="success">{{$i}}</td>
            @endfor
            @for($i = 1; $i <= $dayInMonth; $i++)
            <td align="center" class="danger">{{$i}}</td>
            @endfor
            <td class="warning"></td>
        </tr>
    @if(isset($storage['products']) && count($storage['products']))
        @foreach($storage['products'] as $num => $product)
            <tr>
                <td class="warning">{{$product['materials']['name']}}</td>
                <td class="warning">{{$product['total_begin']}}</td>

                @for($i = 1; $i <= $dayInMonth; $i++)
                    @if(count($product['events']))
                        @if($i < 10)
                            @if(isset($product['events'][$dateStr.'-0'.$i]['+']))
                                <td class="success">
                                    <table width="200px">
                                    @foreach($product['events'][$dateStr.'-0'.$i]['+'] as $event)
                                        <tr>
                                            <td>
                                                {{$event['data']['event_data']['name']}}
                                            </td>
                                            <td>
                                                +{{$event['data']['value']}}
                                            </td>
                                        </tr>
                                        @if(isset($event['data']['event_data']['properties']) && count($event['data']['event_data']['properties']))
                                            @foreach($event['data']['event_data']['properties'] as $propVal)
                                            <tr>
                                                <td>{{$propVal['property']['name']}}</td>
                                                <td>&nbsp:&nbsp</td>
                                                <td>{{$propVal['property']['value']}}</td>
                                            </tr>
                                            @endforeach
                                        @endif

                                    @endforeach
                                    </table>
                                </td>
                            @else
                                <td class="success"></td>
                            @endif
                        @else
                            @if(isset($product['events'][$dateStr.'-'.$i]['+']))
                            <td class="success">
                                <table width="200px">
                                @foreach($product['events'][$dateStr.'-'.$i]['+'] as $event)
                                    <tr>
                                        <td>
                                            {{$event['data']['event_data']['name']}}
                                        </td>
                                        <td>&nbsp</td>
                                        <td>
                                            +{{$event['data']['value']}}
                                        </td>
                                    </tr>
                                    @if(isset($event['data']['event_data']['properties']) && count($event['data']['event_data']['properties']))
                                        @foreach($event['data']['event_data']['properties'] as $propVal)
                                            <tr>
                                                <td>{{$propVal['property']['name']}}</td>
                                                <td>&nbsp:&nbsp</td>
                                                <td>{{$propVal['property']['value']}}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @endforeach
                                </table>
                            </td>
                            @else
                            <td class="success"></td>
                            @endif
                        @endif
                    @else
                        <td class="success"></td>
                    @endif
                @endfor

                @for($i = 1; $i <= $dayInMonth; $i++)
                    @if(count($product['events']))
                        @if($i < 10)
                            @if(isset($product['events'][$dateStr.'-0'.$i]['-']))
                                <td class="danger">
                                    <table width="200px">
                                    @foreach($product['events'][$dateStr.'-0'.$i]['-'] as $event)
                                        <tr>
                                            <td>
                                                {{$event['data']['event_data']['name']}}
                                            </td>
                                            <td>&nbsp</td>
                                            <td>
                                                -{{$event['data']['value']}}
                                            </td>
                                        </tr>
                                        @if(isset($event['data']['event_data']) && count($event['data']['event_data']['properties']))
                                            @foreach($event['data']['event_data']['properties'] as $propVal)
                                            <tr>
                                                <td>{{$propVal['property']['name']}}</td>
                                                <td>&nbsp:&nbsp</td>
                                                <td>{{$propVal['property']['value']}}</td>
                                            </tr>
                                            @endforeach
                                        @endif
                                    @endforeach
                                    </table>
                                </td>
                            @else
                                <td class="danger"></td>
                            @endif
                        @else
                            @if(isset($product['events'][$dateStr.'-'.$i]['-']))
                            <td class="danger">
                                <table width="200px">
                                @foreach($product['events'][$dateStr.'-'.$i]['-'] as $event)
                                    <tr>
                                        <td>
                                            {{$event['data']['event_data']['name']}}
                                        </td>
                                        <td>&nbsp</td>
                                        <td>
                                            -{{$event['data']['value']}}
                                        </td>
                                    </tr>
                                    @if(isset($event['data']['event_data']['properties']) && count($event['data']['event_data']['properties']))
                                        @foreach($event['data']['event_data']['properties'] as $propVal)
                                        <tr>
                                            <td>{{$propVal['property']['name']}}</td>
                                            <td>&nbsp:&nbsp</td>
                                            <td>{{$propVal['property']['value']}}</td>
                                        </tr>
                                        @endforeach
                                    @endif
                                @endforeach
                                </table>
                            </td>
                            @else
                                <td class="danger"></td>
                            @endif
                        @endif
                    @else
                    <td class="danger"></td>
                    @endif
                @endfor

                <td class="warning">{{$product['total_end']}}</td>
            </tr>
        @endforeach
    @endif
    </table>
   @endforeach
</div>
@endif

<script type="text/javascript">

    var materials_in_storage = [];
    @if(count($materials_storage))
        @foreach($materials_storage as $storage_id => $materials)
    materials_in_storage[{{$storage_id}}] = {
        html : ""
    };

    @if(count($materials))
        var html = "";
    html += '{{ Form::label('material_id', 'Материал:') }}';
    html += '{{ Form::select('material_id', $materials, (isset($material_id)) ? $material_id : null, ['class' => 'form-control']) }}';
    html += "{{ $errors->first('storage_id', '<div class=\"text-danger\">:message</div>') }}";
    materials_in_storage[{{$storage_id}}].html = html;
    @endif;
    @endforeach;
    @endif;



    var getHtmlByTypeAndID = function(type, id)
    {
        var valueID = parseInt(id);
        var valueType = type;
        var html = "";
        var data = null;
        var obj = $('#materials');
        var boolIsMaterial = true;
        if(valueType == 'materials')
        {
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
            if(data.html != undefined && data.html.length)
                html = data.html;
        }

        if(boolIsMaterial)
        {
            if(html.length > 0)
                $('.data').removeClass('hide').addClass('show');
            else if(html.length == 0)
                $('.data').removeClass('show').addClass('hide');
        }

        obj.html(html);
    }


$(document).ready(function () {

    $('.datepicker').datepicker( {
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'yy-mm',
        closeText : "Готово",
        currentText : "Сегодня",
        monthNamesShort: ['Янв','Фев','Мар','Апр','Май','Июн','Июл','Авг','Сен','Окт','Ноя','Дек'],
        onClose: function(dateText, inst) {
            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();

            $(this).datepicker('setDate', new Date(year, parseInt(month), 1));
        },
        beforeShow : function(input, inst) {
            if ((datestr = $(this).val()).length > 0) {
                var _reg = /(\d{4})-(\d{2})/;
                var _res = _reg.exec(datestr);
                year = _res[1];
                month = _res[2];
                var dateVal = new Date(year, parseInt(month)-1, 1);
                $(input).datepicker('option', 'defaultDate',dateVal);
                $(input).datepicker('setDate', dateVal);
            }
        }
    });

    getHtmlByTypeAndID('materials',$('#storage_id').val());

    $(".datepicker").focus(function () {
        $(".ui-datepicker-calendar").hide();
        $("#ui-datepicker-div").position({
            my: "center top",
            at: "center bottom",
            of: $(this)
        });
    });

    $('form').submit(function(){
        var storage_id = ($('#storage_id').val() == "all") ? $('#storage_id').val() : parseInt($('#storage_id').val());
        var date = $('.form-group input[name=date]').val();
        var material_id = parseInt($('#material_id').val());
        var additional_url = "";
        if(storage_id > 0 || storage_id == "all")
            additional_url+= '/' + storage_id;

        if(jQuery.trim(date).length)
            additional_url+= '/' + date;

        if(material_id > 0)
            additional_url += '/' + material_id;

        window.location = "{{URL::to('admin/operations/showoperations')}}" + additional_url;
        return false;
    });

});
</script>
@stop
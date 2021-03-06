@extends('admin::layouts.master')
@section('content')
<script type="text/javascript">
    var tableToExcel = (function () {
        var uri = 'data:application/vnd.ms-excel;base64,'
            , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'
            , base64 = function (s) { return window.btoa(unescape(encodeURIComponent(s))) }
            , format = function (s, c) { return s.replace(/{(\w+)}/g, function (m, p) { return c[p]; }) }
        return function (id, name, filename) {
            var table  = document.getElementById("printTable_"+id);
            var ctx = { worksheet: name || 'Worksheet', table: table.innerHTML }

            document.getElementById("dlink_"+id).href = uri + base64(format(template, ctx));
            document.getElementById("dlink_"+id).download = filename;
            document.getElementById("dlink_"+id).click();

        }
    })();
    jQuery(document).ready(function(){
        $('.table.table-bordered tr').click(function(){
            $('.table.table-bordered tr').removeClass('danger').css({'border':''});

            $(this).addClass('danger').css({'border':'2px solid red'});
        });
    });


</script>
<style>
    p {margin: 0px}
</style>
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
<div class="dataTableFormStorage table-responsive">
   @foreach($storagesArrData as $st_id => $storage)
    <h2>Cклад : <span class="error">{{$storage['data']['name']}}</span></h2>
    <a id="dlink_{{$st_id}}"  style="display:none;"></a>
        <table class="table table-bordered" id="printTable_{{$st_id}}" style="border: 2px solid #000000">
            <thead>
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
            </thead>
        @if(isset($storage['products']) && count($storage['products']))
            @foreach($storage['products'] as $type => $products)
            <tr><td colspan="{{$dayInMonth*2}}"><h2>{{$type}}</h2></td></tr>
                @foreach($products as $num => $product)
                    <tr>
                        <td class="warning">{{$product['materials']['name']}}</td>
                        <td class="warning">{{$product['total_begin']}}</td>

                        @for($i = 1; $i <= $dayInMonth; $i++)
                            @if(count($product['events']))
                                @if($i < 10)
                                    @if(isset($product['events'][$dateStr.'-0'.$i]['+']))
                                        <td class="success">
                                            @foreach($product['events'][$dateStr.'-0'.$i]['+'] as $event)
                                                        {{$event['data']['event_data']['name']}}+{{$event['data']['value']}}
                                                        <br/>
                                                @if(isset($event['data']['event_data']['properties']) && count($event['data']['event_data']['properties']))
                                                    @foreach($event['data']['event_data']['properties'] as $propVal)
                                                        {{$propVal['property']['name']}}&nbsp:&nbsp{{$propVal['property']['value']}}
                                                    @endforeach
                                                @endif

                                            @endforeach
                                        </td>
                                    @else
                                        <td class="success"></td>
                                    @endif
                                @else
                                    @if(isset($product['events'][$dateStr.'-'.$i]['+']))
                                    <td class="success" width="200px">
                                        @foreach($product['events'][$dateStr.'-'.$i]['+'] as $event)
                                                {{$event['data']['event_data']['name']}}&nbsp+{{$event['data']['value']}}<br/>
                                            @if(isset($event['data']['event_data']['properties']) && count($event['data']['event_data']['properties']))
                                                @foreach($event['data']['event_data']['properties'] as $propVal)
                                                    {{$propVal['property']['name']}}&nbsp:&nbsp{{$propVal['property']['value']}}
                                                @endforeach
                                            @endif
                                        @endforeach
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
                                            @foreach($product['events'][$dateStr.'-0'.$i]['-'] as $event)
                                                    {{$event['data']['event_data']['name']}}&nbsp-{{$event['data']['value']}}
                                                @if(isset($event['data']['event_data']) && count($event['data']['event_data']['properties']))
                                                    @foreach($event['data']['event_data']['properties'] as $propVal)
                                                        {{$propVal['property']['name']}}&nbsp:&nbsp{{$propVal['property']['value']}}
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        </td>
                                    @else
                                        <td class="danger"></td>
                                    @endif
                                @else
                                    @if(isset($product['events'][$dateStr.'-'.$i]['-']))
                                    <td class="danger">
                                        @foreach($product['events'][$dateStr.'-'.$i]['-'] as $event)
                                                    {{$event['data']['event_data']['name']}}&nbsp-{{$event['data']['value']}} <br/>
                                            @if(isset($event['data']['event_data']['properties']) && count($event['data']['event_data']['properties']))
                                                @foreach($event['data']['event_data']['properties'] as $propVal)
                                                    {{$propVal['property']['name']}}&nbsp:&nbsp{{$propVal['property']['value']}}
                                                @endforeach
                                            @endif
                                        @endforeach
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
            @endforeach
        @endif
        </table>
    <button onclick="tableToExcel({{$st_id}}, 'name', 'myfile.xls');" class="btn btn-lg btn-info">Сохранить в exel</button>
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
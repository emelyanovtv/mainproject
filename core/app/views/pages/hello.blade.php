<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Laravel PHP Framework</title>
    <link rel="stylesheet" href="http://yastatic.net/bootstrap/3.3.4/css/bootstrap.min.css"/>
    <style>
        .table.table-bordered tr.error{
            background: red;
        }
    </style>

</head>
<body>
<style>
    tr.danger{background:#FC0000 !important;}
    tr.success td{background: #0BFC1E !important;}
    tr.warning td{background: #FCEF0B !important;}
    tr.active td{background: #a1a1a1 !important;}


</style>
<!-- Navigation -->
<nav class="navbar navbar-default " role="navigation">
    <div class="container">
        <div class="navbar-header page-scroll">
            <a class="navbar-brand page-scroll" href="#page-top">Информация о материалах</a>
        </div>
    </div>
    <!-- /.container -->
</nav>

<!-- Intro Section -->
<section id="main" class="intro-section">
    <div class="container">
        <div class="row">
                <table class="table table-bordered">
                    <tbody>
                        @foreach ($dataArr as $storage => $itemsGroup)
                            @if (count($itemsGroup) > 0)
                            <tr class="active"><td colspan="3"><h2 style="text-align: center;">{{$storage}}</h2></td></tr>
                            @foreach ($itemsGroup as $group => $items)
                            <tr class="active"><td colspan="3" align="center"><span style="text-align: center;">{{$group}}</span></td></tr>
                                 @foreach ($items as $item)
                                    <tr class="{{{ ($item->materials->is_disabled == 1) ? 'error' : ((intval($item->total) > 0) ? 'success' : 'warning') }}}">
                                        <td>{{$materialConfig['materials'][$item->material_id]['name']}}</td>
                                        <td>Остаток : {{$item->total}}  {{{ ($item->materials->is_disabled == 1) ? '' : ((intval($item->total) > 0) ? '' : ' (уточнить на произвдстве)') }}}</td>
                                        <td>
                                            @if ($item->materials->is_disabled == '1')
                                                Не доступен
                                            @else
                                                @if ((int) $item->total > 0)
                                                    Доступен
                                                @else
                                                    Не доступен
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                            @endif
                        @endforeach
                    </tbody>

                </table>
        </div>
    </div>
</section>


</body>
</html>

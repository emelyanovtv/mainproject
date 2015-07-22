<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Laravel PHP Framework</title>
    <link rel="stylesheet" href="http://yastatic.net/bootstrap/3.3.4/css/bootstrap.min.css"/>
    <script type="text/javascript" src="http://yastatic.net/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <style>
        table tr.danger{background-color:#FC0000};
        table tr.success{background-color: #0BFC1E}
        table tr.warning{background-color: #FCEF0B}
        table tr.active{background-color: #a1a1a1}


    </style>
</head>
<body>
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
                <table class="table table-bordered table-hover">
                    <tbody>
                        @foreach ($dataArr as $storage => $items)
                            @if (count($items) > 0)
                            <tr class="active"><td colspan="3"><h2 style="text-align: center;">{{$storage}}</h2></td></tr>
                            @foreach ($items as $item)
                                <tr class="{{{ ($item->materials->is_disabled == '1') ? 'danger' : ((int)$item->total > 0) ? 'success' : 'warning' }}}">
                                    <td>{{$materialConfig['materials'][$item->material_id]['name']}}</td>
                                    <td>Остаток : {{$item->total}}</td>
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
                            @endif
                        @endforeach
                    </tbody>

                </table>
        </div>
    </div>
</section>


</body>
</html>
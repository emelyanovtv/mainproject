<html>
<head>
    <meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<title> Administrator </title>
	
    {{ style('css/bootstrap.min.css') }}
    {{ style('css/jquery-ui.css') }}
    {{ style('css/'.option('admin.theme', 'default').'.css') }}
	{{ Menu::style() }}
	
	@yield('style')

    {{ script('js/jquery.js') }}
    {{ script('js/jquery-ui.js') }}
    {{ script('js/underscore.js') }}
    {{ script('js/backbone.js') }}
    {{ script('js/bootstrap.min.js') }}
    {{ script('js/all.js') }}



</head>
<body>
	@include('admin::partials.flashes')
	
	@if(Auth::check())
		@include('admin::partials.header')
	@endif

	<div class="container main-content">
		@yield('content')
	</div>
    @yield('script')
	<footer class="container">
		Copyright &COPY; {{ date('Y') }}
	</footer>


</body>
</html>
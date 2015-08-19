<?php

Menu::create('admin-menu', function($menu)
{
	$menu->route('admin.home', trans('menu.home'));

    $menu->dropdown(trans('menu.users'), function ($sub)
    {
        $sub->route('admin.users.index', trans('menu.all_users'));
        $sub->divider();
        $sub->route('admin.roles.index', trans('menu.roles'));
        $sub->route('admin.permissions.index', trans('menu.permissions'));
    });

    $menu->dropdown(trans('menu.storage'), function ($sub)
    {
        $sub->route('admin.storage.index', trans('menu.storage'));
    });


    $menu->dropdown(trans('menu.materials'), function ($sub)
    {
        $sub->route('admin.product.index', trans('menu.materilas'));
        $sub->divider();
        $sub->route('admin.productgroups.index', trans('menu.all_products_groups'));
        $sub->divider();
        $sub->dropdown(trans('menu.properties'), function ($sub)
        {
            $sub->route('admin.measures.index', trans('menu.measures'));
            $sub->divider();
            $sub->route('admin.properties.index', trans('menu.properties_list'));
        });
    });

    $menu->dropdown(trans('menu.events'), function ($sub)
    {
        $sub->route('admin.events.index', trans('menu.all_events'));
        $sub->divider();
        $sub->route('admin.eventprops.index', trans('menu.properties'));

    });
    $menu->dropdown(trans('menu.operations'), function ($sub)
    {
        $sub->route('admin.operations.create', trans('menu.add_operation'));
        $sub->divider();
        $sub->route('admin.operations.showoperations', trans('menu.operations'));
        $sub->route('admin.operations.customshowoperations', trans('menu.customoperations'));
    });

    $menu->check();
});

Menu::create('admin-menu-right', function($menu)	
{
	$menu->setPresenter('Pingpong\Admin\Presenters\NavbarRight');

	$name = isset(Auth::user()->name) ? Auth::user()->name : 'Preferences';
	$menu->dropdown($name, function($sub)
	{
		$sub->route('admin.settings', trans('menu.settings'));
		$sub->divider();
		$sub->route('admin.logout', trans('menu.logout'));
	});
});
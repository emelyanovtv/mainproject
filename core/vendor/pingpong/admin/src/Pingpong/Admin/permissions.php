<?php

if(Auth::check())
{
	Trusty::setView('admin::403');
	try
	{
		Trusty::registerPermissions();
	}
	catch(PDOException $e)
	{
		
	}

    $rulesAdded = [
        ["key" => ['admin/users', 'admin/users/*'], "value" => 'manage_users', 'route' => 'admin.users'],
        ["key" => ['admin/pages', 'admin/pages/*'], "value" => 'manage_pages', 'route' => 'admin.pages'],
        ["key" =>  ['admin/articles', 'admin/articles/*'] , "value" => 'manage_articles', 'route' => 'admin.articles'],
        ["key" => ['admin/categories', 'admin/categories/*'], "value" => 'manage_categories', 'route' => 'admin.categories'],
        ["key" => ['admin/operations', 'admin/operations/*'] , "value" => 'manage_operations', 'route' => 'admin.operations'],
        ["key" => ['admin/storage', 'admin/storage/*'] , "value" => 'manage_storage', 'route' => 'admin.storage'],
        ["key" => ['admin/events', 'admin/events/*'] , "value" => 'manage_events', 'route' => 'admin.events'],
        ["key" => 'admin/eventprops' , "value" => 'manage_eventprops', 'route' => 'admin.eventprops'],
        ["key" => 'admin/product' , "value" => 'manage_product', 'route' => 'admin.product'],
        ["key" => 'admin/productgroups' , "value" => 'manage_productgroups', 'route' => 'admin.productgroups'],
        ["key" => 'admin/measures' , "value" => 'manage_measures', 'route' => 'admin.measures'],
        ["key" => 'admin/properties' , "value" => 'manage_properties', 'route' => 'admin.properties'],
        ["key" => ['admin/permissions', 'admin/permissions/*'] , "value" => 'manage_permissions', 'route' => 'admin.permissions'],
        ["key" => ['admin/roles', 'admin/roles/*'] , "value" => 'manage_roles', 'route' => 'admin.roles'],
        ["key" => 'admin/settings' , "value" => 'manage_settings', 'route' => 'admin.settings'],
    ];

    Auth::user()->setAttribute('rules', $rulesAdded);

    foreach($rulesAdded as $rule)
        Trusty::when($rule['key'], $rule['value']);

}
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
	Trusty::when(['admin/users', 'admin/users/*'], 'manage_users');
	Trusty::when(['admin/pages', 'admin/pages/*'], 'manage_pages');
	Trusty::when(['admin/articles', 'admin/articles/*'], 'manage_articles');
	Trusty::when(['admin/categories', 'admin/categories/*'], 'manage_categories');
    Trusty::when(['admin/operations', 'admin/operations/*'], 'manage_operations');
    Trusty::when(['admin/storage', 'admin/storage/*'], 'manage_storage');
    Trusty::when(['admin/events', 'admin/events/*'], 'manage_events');
    Trusty::when('admin/eventprops', 'manage_eventprops');
    Trusty::when('admin/product', 'manage_product');
    Trusty::when('admin/productgroups', 'manage_productgroups');
    Trusty::when('admin/measures', 'manage_measures');
    Trusty::when('admin/measures', 'manage_measures');
    Trusty::when('admin/properties', 'manage_properties');
	Trusty::when(['admin/permissions', 'admin/permissions/*'], 'manage_permissions');
	Trusty::when(['admin/roles', 'admin/roles/*'], 'manage_roles');
	Trusty::when('admin/settings', 'manage_settings');
}
<?php

Route::group(['prefix' => 'admin', 'namespace' => 'Pingpong\Admin\Controllers'], function()
{
	Route::group(['before' => Config::get('admin::filter.guest')], function()
	{
		Route::resource('login', 'LoginController', ['only' => ['index', 'store']]);
	});

	Route::group(['before' => Config::get('admin::filter.auth')], function()
	{
		Route::get('/',         ['as' => 'admin.home',      'uses' => 'SiteController@index']);
		Route::get('/logout',   ['as' => 'admin.logout',    'uses' => 'SiteController@logout']);

		// settings
		Route::get('/operations/showoperations/{storage_id?}/{date?}/{material_id?}',  ['as' => 'admin.operations.showoperations',  'uses' => 'OperationsController@showoperations']);
        Route::get('settings',  ['as' => 'admin.settings',  'uses' => 'SiteController@settings']);
		Route::post('settings', ['as' => 'admin.settings.update',  'uses' => 'SiteController@updateSettings']);

		// app
        $options = ['except' => ['show']];

		Route::resource('articles', 'ArticlesController', $options);
		Route::resource('pages', 'ArticlesController', $options);
		Route::resource('users', 'UsersController', $options);
		Route::resource('categories', 'CategoriesController', $options);
		Route::resource('roles', 'RolesController', $options);
		Route::resource('permissions', 'PermissionsController', $options);
        Route::resource('product', 'ProductController', $options);
        Route::resource('productgroups', 'ProductGroupsController', $options);
        Route::resource('measures', 'MeasuresController', $options);
        Route::resource('properties', 'PropertiesController', $options);
        Route::resource('events', 'EventsController', $options);
        Route::resource('eventprops', 'EventsPropertiesController', $options);
        Route::resource('storage', 'StorageController', $options);
        Route::resource('operations', 'OperationsController', $options);
        Route::resource('grouptoprop', 'ProductGroupsToPropertyController', $options);
        Route::resource('proptoevent', 'PropertyToEventController', $options);


        //ajax method http header allow
        Route::get('/productgroups/properties', 'ProductGroupsController@getProperties');
        Route::get('/events/properties', 'EventsController@getProperties');
        Route::get('/storage/getstoragesnotin/{id}', 'StorageController@getstoragesnotin');
        Route::get('/product/showall', ['as' => 'admin.product.showall',  'uses' => 'ProductController@showall']);

		// backup & reset
		Route::get('backup/reset', ['as' => 'admin.reset', 'uses' => 'SiteController@reset']);
		Route::get('app/reinstall', ['as' => 'admin.reinstall', 'uses' => 'SiteController@reinstall']);
		Route::get('cache/clear', ['as' => 'admin.cache.clear', 'uses' => 'SiteController@clearCache']);
	});
});
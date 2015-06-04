<?php

return [
	'filter'	=>	[
		'auth'	=>	'admin.auth',
		'guest'	=>	'admin.guest',
	],
	'post'	=>	[
		'view'	=>	'admin::article'
	],
    'custom' => [
        'event'   =>	'13',
        'storage' =>	'3'
    ]
];
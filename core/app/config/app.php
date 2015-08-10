<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => false,

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | your application so that it is used when running Artisan tasks.
    |
    */

    'url' => 'http://localhost',

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. We have gone
    | ahead and set this to a sensible default for you out of the box.
    |
    */

    'timezone' => 'UTC',

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by the translation service provider. You are free to set this value
    | to any of the locales which will be supported by the application.
    |
    */

    'locale' => 'ru',

    /*
    |--------------------------------------------------------------------------
    | Application Fallback Locale
    |--------------------------------------------------------------------------
    |
    | The fallback locale determines the locale to use when the current one
    | is not available. You may change the value to correspond to any of
    | the language folders that are provided through your application.
    |
    */

    'fallback_locale' => 'ru',

    /*
	|--------------------------------------------------------------------------
	| SPECIAL RELATIONS BY MATERIALS ON BOTH SITES
	|--------------------------------------------------------------------------
	|
	| The fallback locale determines the locale to use when the current one
	| is not available. You may change the value to correspond to any of
	| the language folders that are provided through your application.
	|
	*/
    'materialsConfig' => [
        'group_ids' => [2,11,8,3],
        'links' => [
            'www.fotooboi21.ru' => 'www.fotooboi21.ru/wsdl/api.php?action=toggleMaterials&type=web&version=1.0',
            'www.fotooboi.ru' => 'www.fotooboi.ru/wsdl/api.php?action=ToggleMaterials&login=megaplan&pass=Fotooboi2015&type=BitrixApi&version=0.1',
        ],
        'materials' => [
            '1' => [
                'name' => 'Штукатурка',
                'www.fotooboi21.ru' => [14],
                'www.fotooboi.ru' => [1266]
            ],
//            '3' => [
//                'name' => 'Матовый песок',
//                'www.fotooboi21.ru' => [14],
//                'fotooboi' => [1266]
//            ],
            '4' => [
                'name' => 'Крафт',
                'www.fotooboi21.ru' => [38],
                'www.fotooboi.ru' => [1269]
            ],
            '5' => [
                'name' => 'Холст',
                'www.fotooboi21.ru' => [11],
                'www.fotooboi.ru' => [1268]
            ],
            '6' => [
                'name' => 'Живопись маслом',
                'www.fotooboi21.ru' => [26],
                'www.fotooboi.ru' => [1264]
            ],
            '7' => [
                'name' => 'Классическая штукатурка',
                'www.fotooboi21.ru' => [29],
                'www.fotooboi.ru' => [1267]
            ],
            '8' => [
                'name' => 'Венеция',
                'www.fotooboi21.ru' => [15],
                'www.fotooboi.ru' => [1276]
            ],
            '9' => [
                'name' => 'Антика',
                'www.fotooboi21.ru' => [31],
                'www.fotooboi.ru' => [1277]
            ],
            '51' => [
                'name' => 'Лен',
                'www.fotooboi21.ru' => [12],
                'www.fotooboi.ru' => [1265]
            ],
            '56' => [
                'name' => 'Жаккард',
                'www.fotooboi21.ru' => [50],
                'www.fotooboi.ru' => [7127]
            ],
//            '14' => [
//                'name' => 'Прованс',
//                'www.fotooboi21.ru' => [41],
//                'www.fotooboi.ru' => [2651]
//            ],
//            '15' => [
//                'name' => 'Санторини',
//                'www.fotooboi21.ru' => [42],
//                'www.fotooboi.ru' => [2901]
//            ],
//            '49' => [
//                'name' => 'Сицилия',
//                'www.fotooboi21.ru' => [47],
//                'www.fotooboi.ru' => [3643]
//            ],
//            '50' => [
//                'name' => 'Ницца',
//                'www.fotooboi21.ru' => [49],
//                'www.fotooboi.ru' => [3647]
//            ],
//            '52' => [
//                'name' => 'Эконом',
//                'www.fotooboi21.ru' => [43],
//                'www.fotooboi.ru' => [3633]
//            ],
//            '54' => [
//                'name' => 'Корсика',
//                'www.fotooboi21.ru' => [48],
//                'www.fotooboi.ru' => [3646]
//            ],
            '16' => [
                'name' => 'Сатин',
                'www.fotooboi21.ru' => [13],
                'www.fotooboi.ru' => [1272]
            ],
            '18' => [
                'name' => 'Виниловые-матовые',
                'www.fotooboi21.ru' => [27],
                'www.fotooboi.ru' => [1270]
            ],
            '21' => [
                'name' => 'Глянцевые',
                'www.fotooboi21.ru' => [28],
                'www.fotooboi.ru' => [1271]
            ],
            '22' => [
                'name' => 'Гранит(ламинация)',
                'www.fotooboi21.ru' => [36,34],
                'www.fotooboi.ru' => [1274, 1262]
            ],
            '24' => [
                'name' => 'Папирус',
                'www.fotooboi21.ru' => [33,35],
                'www.fotooboi.ru' => [1273, 1261]
            ],
            '35' => [
                'name' => 'Саламандра',
                'www.fotooboi21.ru' => [32,37],
                'www.fotooboi.ru' => [1275, 1263]
            ],

        ]
    ],


    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is used by the Illuminate encrypter service and should be set
    | to a random, 32 character string, otherwise these encrypted strings
    | will not be safe. Please do this before deploying an application!
    |
    */

    'key' => 'YourSecretKey!!!',

    'cipher' => MCRYPT_RIJNDAEL_128,

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */

    'providers' => array(

        'Illuminate\Foundation\Providers\ArtisanServiceProvider',
        'Illuminate\Auth\AuthServiceProvider',
        'Illuminate\Cache\CacheServiceProvider',
        'Illuminate\Session\CommandsServiceProvider',
        'Illuminate\Foundation\Providers\ConsoleSupportServiceProvider',
        'Illuminate\Routing\ControllerServiceProvider',
        'Illuminate\Cookie\CookieServiceProvider',
        'Illuminate\Database\DatabaseServiceProvider',
        'Illuminate\Encryption\EncryptionServiceProvider',
        'Illuminate\Filesystem\FilesystemServiceProvider',
        'Illuminate\Hashing\HashServiceProvider',
        'Illuminate\Html\HtmlServiceProvider',
        'Illuminate\Log\LogServiceProvider',
        'Illuminate\Mail\MailServiceProvider',
        'Illuminate\Database\MigrationServiceProvider',
        'Illuminate\Pagination\PaginationServiceProvider',
        'Illuminate\Queue\QueueServiceProvider',
        'Illuminate\Redis\RedisServiceProvider',
        'Illuminate\Remote\RemoteServiceProvider',
        'Illuminate\Auth\Reminders\ReminderServiceProvider',
        'Illuminate\Database\SeedServiceProvider',
        'Illuminate\Session\SessionServiceProvider',
        'Illuminate\Translation\TranslationServiceProvider',
        'Illuminate\Validation\ValidationServiceProvider',
        'Illuminate\View\ViewServiceProvider',
        'Illuminate\Workbench\WorkbenchServiceProvider',
        'Pingpong\Admin\AdminServiceProvider',
        'Pingpong\Menus\MenusServiceProvider',
        'Pingpong\Trusty\TrustyServiceProvider',
    ),

    /*
    |--------------------------------------------------------------------------
    | Service Provider Manifest
    |--------------------------------------------------------------------------
    |
    | The service provider manifest is used by Laravel to lazy load service
    | providers which are not needed for each request, as well to keep a
    | list of all of the services. Here, you may set its storage spot.
    |
    */

    'manifest' => storage_path().'/meta',

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    |
    */

    'aliases' => array(

        'App'             => 'Illuminate\Support\Facades\App',
        'Artisan'         => 'Illuminate\Support\Facades\Artisan',
        'Auth'            => 'Illuminate\Support\Facades\Auth',
        'Blade'           => 'Illuminate\Support\Facades\Blade',
        'Cache'           => 'Illuminate\Support\Facades\Cache',
        'ClassLoader'     => 'Illuminate\Support\ClassLoader',
        'Config'          => 'Illuminate\Support\Facades\Config',
        'Controller'      => 'Illuminate\Routing\Controller',
        'Cookie'          => 'Illuminate\Support\Facades\Cookie',
        'Crypt'           => 'Illuminate\Support\Facades\Crypt',
        'DB'              => 'Illuminate\Support\Facades\DB',
        'Eloquent'        => 'Illuminate\Database\Eloquent\Model',
        'Event'           => 'Illuminate\Support\Facades\Event',
        'File'            => 'Illuminate\Support\Facades\File',
        'Form'            => 'Illuminate\Support\Facades\Form',
        'Hash'            => 'Illuminate\Support\Facades\Hash',
        'HTML'            => 'Illuminate\Support\Facades\HTML',
        'Input'           => 'Illuminate\Support\Facades\Input',
        'Lang'            => 'Illuminate\Support\Facades\Lang',
        'Log'             => 'Illuminate\Support\Facades\Log',
        'Mail'            => 'Illuminate\Support\Facades\Mail',
        'Paginator'       => 'Illuminate\Support\Facades\Paginator',
        'Password'        => 'Illuminate\Support\Facades\Password',
        'Queue'           => 'Illuminate\Support\Facades\Queue',
        'Redirect'        => 'Illuminate\Support\Facades\Redirect',
        'Redis'           => 'Illuminate\Support\Facades\Redis',
        'Request'         => 'Illuminate\Support\Facades\Request',
        'Response'        => 'Illuminate\Support\Facades\Response',
        'Route'           => 'Illuminate\Support\Facades\Route',
        'Schema'          => 'Illuminate\Support\Facades\Schema',
        'Seeder'          => 'Illuminate\Database\Seeder',
        'Session'         => 'Illuminate\Support\Facades\Session',
        'SoftDeletingTrait' => 'Illuminate\Database\Eloquent\SoftDeletingTrait',
        'SSH'             => 'Illuminate\Support\Facades\SSH',
        'Str'             => 'Illuminate\Support\Str',
        'URL'             => 'Illuminate\Support\Facades\URL',
        'Validator'       => 'Illuminate\Support\Facades\Validator',
        'View'            => 'Illuminate\Support\Facades\View',
        'Menu'              => 'Pingpong\Menus\Facades\Menu',
        'Role'              => 'Pingpong\Trusty\Entities\Role',
        'Permission'        => 'Pingpong\Trusty\Entities\Permission',
        'Trusty'            => 'Pingpong\Trusty\Facades\Trusty',
    ),

);

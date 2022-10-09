<?php

declare(strict_types=1);

return [

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

    'providers' => [
        \App\Providers\WindowServiceProvider::class,
        \App\Providers\RendererServiceProvider::class,
        \App\Providers\ViewportServiceProvider::class,
    ],
];

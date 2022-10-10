<?php

declare(strict_types=1);

use Serafim\SDL\Kernel\Video\WindowFlags;

return [

    /*
    |--------------------------------------------------------------------------
    | Window Name
    |--------------------------------------------------------------------------
    |
    | The name of the window of your application. It will be displayed as the
    | window title and the title of the CLI (php.exe) child process.
    |
    */

    'title'  => env('WINDOW_NAME', 'Bic Engine'),

    /*
    |--------------------------------------------------------------------------
    | Window Width
    |--------------------------------------------------------------------------
    |
    | TODO
    |
    */

    'width'  => (int)env('WINDOW_WIDTH', 1920),

    /*
    |--------------------------------------------------------------------------
    | Window Height
    |--------------------------------------------------------------------------
    |
    | TODO
    |
    */

    'height' => (int)env('WINDOW_HEIGHT', 1080),
];

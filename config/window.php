<?php

/**
 * This file is part of Battleground package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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

    /*
    |--------------------------------------------------------------------------
    | Window Flags
    |--------------------------------------------------------------------------
    |
    | TODO
    |
    */

    'flags'  => WindowFlags::SDL_WINDOW_OPENGL | WindowFlags::SDL_WINDOW_HIDDEN,

    /*
    |--------------------------------------------------------------------------
    | Window Icon
    |--------------------------------------------------------------------------
    |
    | TODO
    |
    */

    'icon' => __DIR__ . '/../resources/img/icon.png'
];

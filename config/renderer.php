<?php

/**
 * This file is part of Battleground package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

use Serafim\SDL\Kernel\Video\RendererFlags;

return [

    /*
    |--------------------------------------------------------------------------
    | Renderer Driver
    |--------------------------------------------------------------------------
    |
    | TODO
    |
    */

    'driver' => null,

    /*
    |--------------------------------------------------------------------------
    | Renderer Flags
    |--------------------------------------------------------------------------
    |
    | TODO
    |
    */

    'flags'  => RendererFlags::SDL_RENDERER_ACCELERATED | RendererFlags::SDL_RENDERER_PRESENTVSYNC,
];

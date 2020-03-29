<?php

/**
 * This file is part of Battleground package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Serafim\Bic;

use FFI\CData;
use Serafim\SDL\Rect as RectStruct;
use Serafim\SDL\RectPtr;
use Serafim\SDL\SDL;

/**
 * Class Util
 */
final class Util
{
    /**
     * @param CData|RectStruct $rect
     * @return CData|RectStruct
     */
    public static function copyRect(CData $rect): CData
    {
        $sdl = SDL::getInstance();

        $new = $sdl->new(RectStruct::class, false);
        $new->w = $rect->w;
        $new->x = $rect->x;
        $new->h = $rect->h;
        $new->y = $rect->y;

        return $new;
    }

    /**
     * @param int $w
     * @param int $h
     * @param int $x
     * @param int $y
     * @return CData|RectPtr
     */
    public static function createRect(int $w = 0, int $h = 0, int $x = 0, int $y = 0): CData
    {
        $sdl = SDL::getInstance();

        /** @var RectStruct $new */
        $new = $sdl->new(RectStruct::class, false);
        $new->w = (int)$w;
        $new->h = (int)$h;
        $new->x = (int)$x;
        $new->y = (int)$y;

        return $sdl::addr($new);
    }
}

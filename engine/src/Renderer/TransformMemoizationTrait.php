<?php

declare(strict_types=1);

namespace Serafim\Bic\Renderer;

use FFI\CData;
use Serafim\Bic\Util;
use Serafim\SDL\RectPtr;
use Serafim\SDL\SDL;

trait TransformMemoizationTrait
{
    /**
     * @var array|RectPtr[]
     */
    protected static array $memoize = [];

    /**
     * @param CData|RectPtr $rect
     * @return CData|RectPtr
     */
    protected function rect(CData $rect): CData
    {
        $id = \spl_object_id($rect);

        if (! isset(self::$memoize[$id])) {
            self::$memoize[$id] = SDL::addr(Util::copyRect($rect));
        }

        return self::$memoize[$id];
    }
}

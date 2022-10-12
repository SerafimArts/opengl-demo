<?php

declare(strict_types=1);

namespace Bic\Image;

use Bic\Image\Format\Info;

enum Format
{
    #[Info(size: 24)]
    case R8G8B8;

    #[Info(size: 24)]
    case B8G8R8;

    #[Info(size: 32)]
    case R8G8B8A8;

    #[Info(size: 32)]
    case B8G8R8A8;

    #[Info(size: 32)]
    case A8B8G8R8;

    /**
     * @return Info
     */
    private function getInfo(): Info
    {
        static $attributes = null;
        $attributes ??= new \WeakMap();

        if (!isset($attributes[$this])) {
            $reflection = new \ReflectionEnumUnitCase($this, $this->name);
            foreach ($reflection->getAttributes(Info::class) as $attribute) {
                return $attributes[$this] = $attribute->newInstance();
            }
        }

        return $attributes[$this] ??= new Info();
    }

    /**
     * The number of significant bits in a pixel
     * value, eg: 8, 15, 16, 24, 32.
     *
     * @return positive-int
     */
    public function getBitsPerPixel(): int
    {
        $info = $this->getInfo();

        return $info->size;
    }
}

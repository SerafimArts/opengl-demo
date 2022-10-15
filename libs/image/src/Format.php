<?php

declare(strict_types=1);

namespace Bic\Image;

use Bic\Image\Format\Info;

enum Format
{
    #[Info(size: 3)]
    case R8G8B8;

    #[Info(size: 3)]
    case B8G8R8;

    #[Info(size: 4)]
    case R8G8B8A8;

    #[Info(size: 4)]
    case B8G8R8A8;

    #[Info(size: 4)]
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
     * The number of significant bytes in a pixel value.
     *
     * @return positive-int
     */
    public function getBytesPerPixel(): int
    {
        $info = $this->getInfo();

        return $info->size;
    }
}

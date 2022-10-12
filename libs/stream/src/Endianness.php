<?php

declare(strict_types=1);

namespace Bic\Stream;

enum Endianness
{
    case BIG;
    case LITTLE;

    /**
     * @return static
     */
    public static function auto(): self
    {
        if (\unpack('S', "\x01\x00")[1] === 1) {
            return self::LITTLE;
        }

        return self::BIG;
    }
}

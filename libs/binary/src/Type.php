<?php

declare(strict_types=1);

namespace Bic\Binary;

enum Type
{
    /**
     * Signed platform-dependent integer
     */
    case INT;

    /**
     * Unsigned platform-dependent integer
     */
    case UINT;

    /**
     * Signed char
     */
    case INT8;

    /**
     * Unsigned char
     */
    case UINT8;

    /**
     * Signed short
     */
    case INT16;

    /**
     * Unsigned short
     */
    case UINT16;

    /**
     * Signed long
     */
    case INT32;

    /**
     * Unsigned long
     */
    case UINT32;

    /**
     * Signed long long
     */
    case INT64;

    /**
     * Unsigned long long
     */
    case UINT64;

    /**
     * Float
     */
    case FLOAT32;

    /**
     * Double
     */
    case FLOAT64;

    /**
     * @return positive-int|0
     */
    public function getSize(): int
    {
        return match ($this) {
            self::INT,
            self::UINT => \PHP_INT_SIZE,

            self::INT16,
            self::UINT16 => 2,

            self::FLOAT32,
            self::INT32,
            self::UINT32 => 4,

            self::FLOAT64,
            self::INT64,
            self::UINT64 => 8,

            default => 0,
        };
    }

    /**
     * @param Type $type
     *
     * @return positive-int|0
     */
    public static function sizeOf(self $type): int
    {
        return $type->getSize();
    }

    public function toPackFormat(Endianness $endianness = null): string
    {
        $endianness ??= Endianness::auto();

        return match ($this) {
            Type::INT => \PHP_INT_SIZE > 4
                ? self::INT64->toPackFormat($endianness)
                : self::INT32->toPackFormat($endianness),
            Type::UINT => \PHP_INT_SIZE > 4
                ? self::UINT64->toPackFormat($endianness)
                : self::UINT32->toPackFormat($endianness),
            Type::INT8 => 'c',
            Type::UINT8 => 'C',
            Type::INT16 => 's',
            Type::UINT16 => $endianness === Endianness::BIG ? 'n' : 'v',
            Type::INT32 => 'l',
            Type::UINT32 => $endianness === Endianness::BIG ? 'N' : 'V',
            Type::INT64 => 'q',
            Type::UINT64 => $endianness === Endianness::BIG ? 'J' : 'P',
            Type::FLOAT32 => $endianness === Endianness::BIG ? 'G' : 'Pg',
            Type::FLOAT64 => $endianness === Endianness::BIG ? 'E' : 'e',
        };
    }
}

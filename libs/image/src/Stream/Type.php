<?php

declare(strict_types=1);

namespace Bic\Image\Stream;

use JetBrains\PhpStorm\ExpectedValues;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Bic\Image
 */
enum Type: string
{
    /**
     * Signed integer (machine dependent size and byte order)
     */
    case INT = 'i';

    /**
     * Unsigned integer (machine dependent size and byte order)
     */
    case UINT = 'I';

    /**
     * Signed char
     */
    case INT8 = 'c';

    /**
     * Unsigned char
     */
    case UINT8 = 'C';

    /**
     * Signed short (always 16 bit, machine byte order)
     */
    case INT16 = 's';

    /**
     * Unsigned short (always 16 bit, big endian byte order)
     */
    case UINT16_BE = 'n';

    /**
     * Unsigned short (always 16 bit, little endian byte order)
     */
    case UINT16_LE = 'v';

    /**
     * Unsigned short (always 16 bit, machine byte order)
     */
    case UINT16_ME = 'S';

    /**
     * Signed long (always 32 bit, machine byte order)
     */
    case INT32 = 'l';

    /**
     * Unsigned long (always 32 bit, big endian byte order)
     */
    case UINT32_BE = 'N';

    /**
     * Unsigned long (always 32 bit, little endian byte order)
     */
    case UINT32_LE = 'V';

    /**
     * Unsigned long (always 32 bit, machine byte order)
     */
    case UINT32_ME = 'L';

    /**
     * Signed long long (always 64 bit, machine byte order)
     */
    case INT64 = 'q';

    /**
     * Unsigned long long (always 64 bit, big endian byte order)
     */
    case UINT64_BE = 'J';

    /**
     * Unsigned long long (always 64 bit, little endian byte order)
     */
    case UINT64_LE = 'P';

    /**
     * Unsigned long long (always 64 bit, machine byte order)
     */
    case UINT64_ME = 'Q';

    /**
     * Float (machine dependent size, big endian byte order)
     */
    case FLOAT32_BE = 'G';

    /**
     * Float (machine dependent size, little endian byte order)
     */
    case FLOAT32_LE = 'g';

    /**
     * Float (machine dependent size and representation)
     */
    case FLOAT32_ME = 'f';

    /**
     * Double (machine dependent size, big endian byte order)
     */
    case FLOAT64_BE = 'E';

    /**
     * Double (machine dependent size, little endian byte order)
     */
    case FLOAT64_LE = 'e';

    /**
     * Double (machine dependent size and representation)
     */
    case FLOAT64_ME = 'd';

    /**
     * @return positive-int
     */
    public function getSize(): int
    {
        return match ($this) {
            self::INT,
            self::UINT => \PHP_INT_SIZE,

            self::INT16,
            self::UINT16_BE,
            self::UINT16_LE,
            self::UINT16_ME => 2,

            self::FLOAT32_BE,
            self::FLOAT32_LE,
            self::FLOAT32_ME,
            self::INT32,
            self::UINT32_BE,
            self::UINT32_LE,
            self::UINT32_ME => 4,

            self::FLOAT64_BE,
            self::FLOAT64_LE,
            self::FLOAT64_ME,
            self::INT64,
            self::UINT64_BE,
            self::UINT64_LE,
            self::UINT64_ME => 8,

            default => 1,
        };
    }

    /**
     * @param string|self $type
     * @return positive-int
     */
    public static function sizeOf(
        #[ExpectedValues(valuesFromClass: Type::class)]
        string|self $type
    ): int {
        return self::of($type)->getSize();
    }

    /**
     * @param string|self $type
     *
     * @return static
     */
    public static function of(
        #[ExpectedValues(valuesFromClass: Type::class)]
        string|self $type,
    ): self {
        if (\is_string($type)) {
            return self::from($type);
        }

        return $type;
    }
}

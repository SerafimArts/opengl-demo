<?php

declare(strict_types=1);

namespace Bic\Tiled\Common;

/**
 * @psalm-consistent-constructor
 */
class Color implements \Stringable
{
    /**
     * @param int $r
     * @param int $g
     * @param int $b
     * @param int $a
     */
    public function __construct(
        public int $r = 0,
        public int $g = 0,
        public int $b = 0,
        public int $a = 0,
    ) {
    }

    /**
     * @param string $color
     * @return static
     */
    public static function fromHexString(string $color): static
    {
        if (($color[0] ?? '') !== '#') {
            throw new \InvalidArgumentException(
                \sprintf('The color value must start with an "#", but "%s" given', $color)
            );
        }

        $value = \hexdec(\substr($color, 1));

        if ($value > 0xFFFFFF) {
            return new static(
                $value >> 24 & 0xFF,
                $value >> 16 & 0xFF,
                $value >> 8 & 0xFF,
                $value & 0xFF,
            );
        }

        return new static(
            $value >> 16 & 0xFF,
            $value >> 8 & 0xFF,
            $value & 0xFF,
        );
    }

    /**
     * @param bool $lower
     * @return string
     */
    public function toHexString(bool $lower = false): string
    {
        $args = [$this->r, $this->g, $this->b, $this->a];

        $result = $this->a > 0
            ? \vsprintf('#%02x%02x%02x%02x', $args)
            : \vsprintf('#%02x%02x%02x', $args);

        return $lower ? $result : \strtoupper($result);
    }

    /**
     * @return non-empty-string
     */
    public function __toString(): string
    {
        return $this->toHexString();
    }
}

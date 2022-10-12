<?php

declare(strict_types=1);

namespace Bic\Math;

class Vec2 extends Vector
{
    /**
     * Constructs a vector from the given vector.
     *
     * @param Vector $vec
     * @return self
     */
    public static function from(Vector $vec): self
    {
        return new self($vec->x, $vec->y);
    }

    /**
     * @return static
     */
    public static function x(): self
    {
        return new self(x: 1);
    }

    /**
     * @return static
     */
    public static function y(): self
    {
        return new self(y: 1);
    }

    /**
     * {@inheritDoc}
     */
    public function length(): float
    {
        return \sqrt($this->x * $this->x + $this->y * $this->y);
    }

    /**
     * {@inheritDoc}
     */
    public function length2(): float
    {
        return $this->x * $this->x + $this->y * $this->y;
    }

    /**
     * {@inheritDoc}
     */
    public function normalize(): void
    {
        $length = $this->length();

        if ($length !== .0) {
            $this->x /= $length;
            $this->y /= $length;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function sub(Vector $vector): void
    {
        $this->x -= $vector->x;
        $this->y -= $vector->y;
    }

    /**
     * @param Vector $vector
     * @return float
     */
    public function dot(Vector $vector): float
    {
        return $this->x * $vector->x
             + $this->y * $vector->y;
    }

    /**
     * Calculates the 2D cross product between this and the given vector.
     *
     * @param Vector $vector
     * @return float
     */
    public function cross(Vector $vector): float
    {
        return $this->x * $vector->y
             - $this->y * $vector->x;
    }

    /**
     * {@inheritDoc}
     */
    public function add(Vector $vector): void
    {
        $this->x += $vector->x;
        $this->y += $vector->y;
    }

    /**
     * {@inheritDoc}
     */
    public function scale(Vector $vector): void
    {
        $this->x *= $vector->x;
        $this->y *= $vector->y;
    }

    /**
     * {@inheritDoc}
     */
    public function scaleBy(float $value): void
    {
        $this->x *= $value;
        $this->y *= $value;
    }

    /**
     * {@inheritDoc}
     */
    public function __toString(): string
    {
        return \sprintf('vec2(%F, %F)', $this->x, $this->y);
    }
}

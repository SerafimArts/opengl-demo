<?php

declare(strict_types=1);

namespace Bic\Math;

class Vec3 extends Vector
{
    /**
     * Constructs a new vector at (0, 0, 0) or at the given components.
     *
     * @param float $x
     * @param float $y
     * @param float $z
     */
    public function __construct(
        float $x = .0,
        float $y = .0,
        public float $z = .0,
    ) {
        parent::__construct($x, $y);
    }

    /**
     * @return static
     */
    public static function one(): static
    {
        return new static(1.0, 1.0, 1.0);
    }

    /**
     * @param Vector $vec
     * @return static
     */
    public static function from(Vector $vec): self
    {
        return new self($vec->x, $vec->y, $vec->z ?? 0.0);
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
     * @return static
     */
    public static function z(): self
    {
        return new self(z: 1);
    }

    /**
     * {@inheritDoc}
     */
    public function sub(Vector $vector): void
    {
        $this->x -= $vector->x;
        $this->y -= $vector->y;
        $this->z -= $vector->z ?? 0.0;
    }

    /**
     * @param Vector $vector
     * @return float
     */
    public function dot(Vector $vector): float
    {
        return $this->x * $vector->x
             + $this->y * $vector->y
             + $this->z * ($vector->z ?? 0.0);
    }

    /**
     * {@inheritDoc}
     */
    public function add(Vector $vector): void
    {
        $this->x += $vector->x;
        $this->y += $vector->y;
        $this->z += $vector->z ?? 0.0;
    }

    /**
     * Creates the cross product between it and the other vector.
     *
     * @param Vector $vec
     * @return void
     */
    public function cross(Vector $vec): void
    {
        $this->x = $this->y * ($vec->z ?? 0.0) - $this->z * $vec->y;
        $this->y = $this->z * $vec->x - $this->x * ($vec->z ?? 0.0);
        $this->z = $this->x * $vec->y - $this->y * $vec->x;
    }

    /**
     * {@inheritDoc}
     */
    public function length(): float
    {
        return \sqrt($this->x * $this->x + $this->y * $this->y + $this->z * $this->z);
    }

    /**
     * {@inheritDoc}
     */
    public function length2(): float
    {
        return $this->x * $this->x
             + $this->y * $this->y
             + $this->z * $this->z;
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
            $this->z /= $length;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function scale(Vector $vector): void
    {
        assert($vector instanceof Vec2);

        $this->x *= $vector->x;
        $this->y *= $vector->y;
        $this->z *= ($vector->z ?? 0.0);
    }

    /**
     * {@inheritDoc}
     */
    public function scaleBy(float $value): void
    {
        $this->x *= $value;
        $this->y *= $value;
        $this->z *= $value;
    }

    /**
     * {@inheritDoc}
     */
    public function __toString(): string
    {
        return \sprintf('vec3(%F, %F, %F)', $this->x, $this->y, $this->z);
    }
}

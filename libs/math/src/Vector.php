<?php

declare(strict_types=1);

namespace Bic\Math;

/**
 * @psalm-consistent-constructor
 */
abstract class Vector implements \Stringable
{
    /**
     * Constructs a new vector at (0, 0) or at the given components.
     *
     * @param float $x
     * @param float $y
     */
    public function __construct(
        public float $x = .0,
        public float $y = .0,
    ) {
    }

    /**
     * @return static
     */
    public static function zero(): static
    {
        return new static();
    }

    /**
     * @return static
     */
    public static function one(): static
    {
        return new static(1.0, 1.0);
    }

    /**
     * Returns a copy of this vector.
     *
     * @psalm-immutable
     */
    public function copy(): static
    {
        return clone $this;
    }

    /**
     * Limits the length of this vector, based on the desired maximum length.
     */
    public function limit(float $limit): void
    {
        $this->limit2($limit * $limit);
    }

    /**
     * Limits the length of this vector, based on the desired maximum length
     * squared. This method is slightly faster than {@see Vector::limit()}
     *
     * @param float $limit2 Squared desired maximum length for this vector
     */
    public function limit2(float $limit2): void
    {
        $length2 = $this->length2();

        if ($length2 > $limit2) {
            $this->scaleBy(\sqrt($limit2 / $length2));
        }
    }

    /**
     * Clamps this vector's length to given min and max values.
     */
    public function clamp(float $min, float $max): void
    {
        $length2 = $this->length2();

        if ($length2 === 0.0) {
            return;
        }

        if ($length2 > ($max2 = $max * $max)) {
            $this->scaleBy(\sqrt($max2 / $length2));
        } elseif ($length2 < ($min2 = $min * $min)) {
            $this->scaleBy(\sqrt($min2 / $length2));
        }
    }

    /**
     * Returns the euclidean length.
     */
    abstract public function length(): float;

    /**
     * Normalizes this vector. Does nothing if it is zero.
     */
    abstract public function normalize(): void;

    /**
     * Subtracts the given vector from this vector.
     */
    abstract public function sub(self $vector): void;

    /**
     * Adds the given vector to this vector.
     */
    abstract public function add(self $vector): void;

    /**
     * This method is faster than {@see VectorInterface::length()} because it
     * avoids calculating a square root. It is useful for comparisons, but not
     * for getting exact lengths, as the return value is the square of the
     * actual length.
     */
    abstract public function length2(): float;

    /**
     * Returns the dot product between this and the other vector.
     */
    abstract public function dot(self $vector): float;

    /**
     * Scales this vector by another vector.
     */
    abstract public function scale(self $vector): void;

    /**
     * Scales this vector by a scalar.
     */
    abstract public function scaleBy(float $value): void;
}

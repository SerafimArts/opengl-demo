<?php

declare(strict_types=1);

namespace Bic\Math;

/**
 * @formatter:off
 * @psalm-type MatrixIndex = Mat4::M*
 *
 * @template-implements \ArrayAccess<MatrixIndex, float>
 * @template-implements \IteratorAggregate<MatrixIndex, float>
 */
final class Mat4 implements \Stringable, \ArrayAccess, \IteratorAggregate
{
    /**
     * XX: Typically the unrotated X component for scaling, also the cosine of
     * the angle when rotated on the Y and/or Z axis. On Vector3 multiplication
     * this value is multiplied with the source X component and added to the
     * target X component.
     */
    public const M00 = 0;

    /**
     * XY: Typically the negative sine of the angle when rotated on the Z axis.
     * On Vector3 multiplication this value is multiplied with the source Y
     * component and added to the target X component.
     */
    public const M01 = 4;

    /**
     * XZ: Typically the sine of the angle when rotated on the Y axis. On
     * Vector3 multiplication this value is multiplied with the source Z
     * component and added to the target X component.
     */
    public const M02 = 8;

    /**
     * XW: Typically the translation of the X component. On Vector3 multiplication
     * this value is added to the target X component.
     */
    public const M03 = 12;

    /**
     * YX: Typically the sine of the angle when rotated on the Z axis. On
     * Vector3 multiplication this value is multiplied with the source X
     * component and added to the target Y component.
     */
    public const M10 = 1;

    /**
     * YY: Typically the unrotated Y component for scaling, also the cosine of
     * the angle when rotated on the X and/or Z axis. On Vector3 multiplication
     * this value is multiplied with the source Y component and added to the
     * target Y component.
     */
    public const M11 = 5;

    /**
     * YZ: Typically the negative sine of the angle when rotated on the X axis.
     * On Vector3 multiplication this value is multiplied with the source Z
     * component and added to the target Y component.
     */
    public const M12 = 9;

    /**
     * YW: Typically the translation of the Y component. On Vector3
     * multiplication this value is added to the target Y component.
     */
    public const M13 = 13;

    /**
     * ZX: Typically the negative sine of the angle when rotated on the Y axis.
     * On Vector3 multiplication this value is multiplied with the source X
     * component and added to the target Z component.
     */
    public const M20 = 2;

    /**
     * ZY: Typical the sine of the angle when rotated on the X axis. On Vector3
     * multiplication this value is multiplied with the source Y component and
     * added to the target Z component.
     */
    public const M21 = 6;

    /**
     * ZZ: Typically the unrotated Z component for scaling, also the cosine of
     * the angle when rotated on the X and/or Y axis. On Vector3 multiplication
     * this value is multiplied with the source Z component and added to the
     * target Z component.
     */
    public const M22 = 10;

    /**
     * ZW: Typically the translation of the Z component. On Vector3
     * multiplication this value is added to the target Z component.
     */
    public const M23 = 14;

    /**
     * WX: Typically the value zero. On Vector3 multiplication this value is
     * ignored.
     */
    public const M30 = 3;

    /**
     * WY: Typically the value zero. On Vector3 multiplication this value is
     * ignored.
     */
    public const M31 = 7;

    /**
     * WZ: Typically the value zero. On Vector3 multiplication this value is
     * ignored.
     */
    public const M32 = 11;

    /**
     * WW: Typically the value one. On Vector3 multiplication this value is
     * ignored.
     */
    public const M33 = 15;

    /**
     * @var array<float>
     */
    public const MX_NORMAL = [
        1.0, 0.0, 0.0, 0.0,
        0.0, 1.0, 0.0, 0.0,
        0.0, 0.0, 1.0, 0.0,
        0.0, 0.0, 0.0, 1.0,
    ];

    /**
     * @var array<float>
     */
    public const MX_ZERO = [
        0.0, 0.0, 0.0, 0.0,
        0.0, 0.0, 0.0, 0.0,
        0.0, 0.0, 0.0, 0.0,
        0.0, 0.0, 0.0, 0.0,
    ];

    /**
     * @var array<float>
     */
    public array $values = self::MX_NORMAL;

    /**
     * @param array<float>|null $values
     */
    public function __construct(array $values = null)
    {
        if ($values !== null) {
            $this->values = $values;
        }
    }

    /**
     * @param array|null $values
     * @return static
     */
    public static function new(array $values = null): self
    {
        return new self($values);
    }

    /**
     * @param Vec3 $eye
     * @param Vec3 $center
     * @param Vec3 $up
     * @return static
     */
    public static function lookAt(Vec3 $eye, Vec3 $center, Vec3 $up): self
    {
        $cameraDirection = clone $eye;
        $cameraDirection->sub($center);
        $cameraDirection->normalize();

        $cameraRight = clone $up;
        $cameraRight->cross($cameraDirection);
        $cameraRight->normalize();

        $cameraUp = clone $cameraDirection;
        $cameraUp->cross($cameraRight);

        $orientation = new self([
            $cameraRight->x, $cameraUp->x, $cameraDirection->x, 0.0,
            $cameraRight->y, $cameraUp->y, $cameraDirection->y, 0.0,
            $cameraRight->z, $cameraUp->z, $cameraDirection->z, 0.0,
            0.0, 0.0, 0.0, 1.0,
        ]);

        $orientation->multiply(new self([
            1.0, 0.0, 0.0, 0.0,
            0.0, 1.0, 0.0, 0.0,
            0.0, 0.0, 1.0, 0.0,
            -$cameraRight->dot($eye), -$cameraUp->dot($eye), -$cameraDirection->dot($eye), 1.0,
        ]));

        return $orientation;
    }

    /**
     * @param float $fov Fov in radians
     * @param float $aspect
     * @param float $near
     * @param float $far
     * @return static
     */
    public static function perspective(float $fov = 45., float $aspect = 16/9, float $near = .1, float $far = 100.): self
    {
        $f = 1.0 / \tan($fov / 2.0);

        return new self([
            $f / $aspect, 0.0, 0.0, 0.0,
            0.0, $f, 0.0, 0.0,
            0.0, 0.0, ($far + $near) / ($near - $far), -1.0,
            0.0, 0.0, (-2.0 * $far * $near) / ($far - $near), 0.0,
        ]);
    }

    /**
     * Postmultiplies this matrix with a (counter-clockwise) rotation matrix.
     *
     * @param float $angle Angle in radians
     * @param Vec3 $normal
     */
    public function rotate(float $angle, Vec3 $normal): void
    {
        if ($normal->x) {
            $this->rotateX($angle);
        }

        if ($normal->y) {
            $this->rotateY($angle);
        }

        if ($normal->z) {
            $this->rotateZ($angle);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function rotateX(float $angle): void
    {
        if ($angle === 0.0) {
            return;
        }

        $this->multiply(new self([
            1.0, 0.0, 0.0, 0.0,
            0.0, \cos($angle), -\sin($angle), 0.0,
            0.0, \sin($angle), \cos($angle), 0.0,
            0.0, 0.0, 0.0, 1.0,
        ]));
    }

    /**
     * {@inheritDoc}
     */
    public function rotateY(float $angle): void
    {
        if ($angle === 0.0) {
            return;
        }

        $this->multiply(new self([
            \cos($angle), 0.0, \sin($angle), 0.0,
            0.0, 1.0, 0.0, 0.0,
            -\sin($angle), 0.0, \cos($angle), 0.0,
            0.0, 0.0, 0.0, 1.0,
        ]));
    }

    /**
     * {@inheritDoc}
     */
    public function rotateZ(float $angle): void
    {
        if ($angle === 0.0) {
            return;
        }

        $this->multiply(new self([
            \cos($angle), -\sin($angle), 0.0, 0.0,
            \sin($angle), \cos($angle), 0.0, 0.0,
            0.0, 0.0, 1.0, 0.0,
            0.0, 0.0, 0.0, 1.0,
        ]));
    }

    /**
     * {@inheritDoc}
     */
    public function multiply(Mat4 $matrix): void
    {
        assert($matrix instanceof self);

        // Loop inlining optimization
        $this->values[self::M00]
                = $this->values[self::M00] * $matrix->values[self::M00]
                + $this->values[self::M10] * $matrix->values[self::M01]
                + $this->values[self::M20] * $matrix->values[self::M02]
                + $this->values[self::M30] * $matrix->values[self::M03];
        $this->values[self::M10]
            = $this->values[self::M00] * $matrix->values[self::M10]
            + $this->values[self::M10] * $matrix->values[self::M11]
            + $this->values[self::M20] * $matrix->values[self::M12]
            + $this->values[self::M30] * $matrix->values[self::M13];
        $this->values[self::M20]
            = $this->values[self::M00] * $matrix->values[self::M20]
            + $this->values[self::M10] * $matrix->values[self::M21]
            + $this->values[self::M20] * $matrix->values[self::M22]
            + $this->values[self::M30] * $matrix->values[self::M23];
        $this->values[self::M30]
            = $this->values[self::M00] * $matrix->values[self::M30]
            + $this->values[self::M10] * $matrix->values[self::M31]
            + $this->values[self::M20] * $matrix->values[self::M32]
            + $this->values[self::M30] * $matrix->values[self::M33];

        $this->values[self::M01]
            = $this->values[self::M01] * $matrix->values[self::M00]
            + $this->values[self::M11] * $matrix->values[self::M01]
            + $this->values[self::M21] * $matrix->values[self::M02]
            + $this->values[self::M31] * $matrix->values[self::M03];
        $this->values[self::M11]
            = $this->values[self::M01] * $matrix->values[self::M10]
            + $this->values[self::M11] * $matrix->values[self::M11]
            + $this->values[self::M21] * $matrix->values[self::M12]
            + $this->values[self::M31] * $matrix->values[self::M13];
        $this->values[self::M21]
            = $this->values[self::M01] * $matrix->values[self::M20]
            + $this->values[self::M11] * $matrix->values[self::M21]
            + $this->values[self::M21] * $matrix->values[self::M22]
            + $this->values[self::M31] * $matrix->values[self::M23];
        $this->values[self::M31]
            = $this->values[self::M01] * $matrix->values[self::M30]
            + $this->values[self::M11] * $matrix->values[self::M31]
            + $this->values[self::M21] * $matrix->values[self::M32]
            + $this->values[self::M31] * $matrix->values[self::M33];

        $this->values[self::M02]
            = $this->values[self::M02] * $matrix->values[self::M00]
            + $this->values[self::M12] * $matrix->values[self::M01]
            + $this->values[self::M22] * $matrix->values[self::M02]
            + $this->values[self::M32] * $matrix->values[self::M03];
        $this->values[self::M12]
            = $this->values[self::M02] * $matrix->values[self::M10]
            + $this->values[self::M12] * $matrix->values[self::M11]
            + $this->values[self::M22] * $matrix->values[self::M12]
            + $this->values[self::M32] * $matrix->values[self::M13];
        $this->values[self::M22]
            = $this->values[self::M02] * $matrix->values[self::M20]
            + $this->values[self::M12] * $matrix->values[self::M21]
            + $this->values[self::M22] * $matrix->values[self::M22]
            + $this->values[self::M32] * $matrix->values[self::M23];
        $this->values[self::M32]
            = $this->values[self::M02] * $matrix->values[self::M30]
            + $this->values[self::M12] * $matrix->values[self::M31]
            + $this->values[self::M22] * $matrix->values[self::M32]
            + $this->values[self::M32] * $matrix->values[self::M33];

        $this->values[self::M03]
            = $this->values[self::M03] * $matrix->values[self::M00]
            + $this->values[self::M13] * $matrix->values[self::M01]
            + $this->values[self::M23] * $matrix->values[self::M02]
            + $this->values[self::M33] * $matrix->values[self::M03];
        $this->values[self::M13]
            = $this->values[self::M03] * $matrix->values[self::M10]
            + $this->values[self::M13] * $matrix->values[self::M11]
            + $this->values[self::M23] * $matrix->values[self::M12]
            + $this->values[self::M33] * $matrix->values[self::M13];
        $this->values[self::M23]
            = $this->values[self::M03] * $matrix->values[self::M20]
            + $this->values[self::M13] * $matrix->values[self::M21]
            + $this->values[self::M23] * $matrix->values[self::M22]
            + $this->values[self::M33] * $matrix->values[self::M23];
        $this->values[self::M33]
            = $this->values[self::M03] * $matrix->values[self::M30]
            + $this->values[self::M13] * $matrix->values[self::M31]
            + $this->values[self::M23] * $matrix->values[self::M32]
            + $this->values[self::M33] * $matrix->values[self::M33];
    }

    /**
     * Postmultiplies this matrix by a translation matrix.
     */
    public function translate(Vec3 $vec3): void
    {
        $this->multiply(new self([
            1.0, 0.0, 0.0, 0.0,
            0.0, 1.0, 0.0, 0.0,
            0.0, 0.0, 1.0, 0.0,
            $vec3->x, $vec3->y, $vec3->z, 1.0,
        ]));
    }

    /**
     * Postmultiplies this matrix with a scale matrix.
     */
    public function scale(Vec3 $vec3): void
    {
        $this->multiply(new self([
            $vec3->x, $vec3->x, $vec3->x, $vec3->x,
            $vec3->y, $vec3->y, $vec3->y, $vec3->y,
            $vec3->z, $vec3->z, $vec3->z, $vec3->z,
            1.0, 1.0, 1.0, 1.0,
        ]));
    }

    /**
     * {@inheritDoc}
     */
    public function offsetExists($offset): bool
    {
        assert(is_int($offset));

        return $offset >= self::M00 && $offset <= self::M33;
    }

    /**
     * {@inheritDoc}
     */
    public function offsetGet($offset): float
    {
        assert(is_int($offset));

        return $this->values[$offset] ?? 0.0;
    }

    /**
     * {@inheritDoc}
     */
    public function offsetSet($offset, $value): void
    {
        assert(is_int($offset));
        assert(is_float($value) || is_int($value));

        $this->values[$offset] = $value;
    }

    /**
     * {@inheritDoc}
     */
    public function offsetUnset($offset): void
    {
        assert(is_int($offset));

        $this->values[$offset] = 0.0;
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->values);
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return $this->values;
    }

    /**
     * {@inheritDoc}
     */
    public function __toString(): string
    {
        [$result, $index] = [[], 0];

        for ($x = 0; $x < 4; ++$x) {
            $current = [];

            for ($y = 0; $y < 4; ++$y) {
                $current[] = \sprintf('%g', $this->values[$index++]);
            }

            $result[] = '[ ' . \implode(', ', $current) . ' ]';
        }

        return \implode("\n", $result);
    }
}

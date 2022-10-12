<?php

declare(strict_types=1);

namespace Bic\Math;

/**
 * @param float $x
 * @param float $y
 * @return Vec2
 */
function vec2(float $x = .0, float $y = .0): Vec2
{
    return new Vec2($x, $y);
}

/**
 * @param float $x
 * @param float $y
 * @param float $z
 * @return Vec3
 */
function vec3(float $x = .0, float $y = .0, float $z = .0): Vec3
{
    return new Vec3($x, $y, $z);
}

/**
 * @param array<float>|null $points
 * @return Mat4
 */
function mat4(array $points = null): Mat4
{
    return new Mat4($points);
}

/**
 * @param float $fov
 * @param float $aspect
 * @param float $near
 * @param float $far
 * @return Mat4
 */
function perspective(float $fov = 45., float $aspect = 16/9, float $near = .1, float $far = 100.): Mat4
{
    return Mat4::perspective($fov, $aspect, $near, $far);
}

/**
 * @param Vec3 $eye
 * @param Vec3 $center
 * @param Vec3 $up
 * @return Mat4
 */
function look_at(Vec3 $eye, Vec3 $center, Vec3 $up): Mat4
{
    return Mat4::lookAt($eye, $center, $up);
}

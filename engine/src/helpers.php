<?php

declare(strict_types=1);

namespace Serafim\Bic;

use Serafim\Bic\Math\Vector;
use Serafim\Bic\Math\Vector2;
use Serafim\Bic\Math\Vector3;

function vec(float $x = 0): Vector
{
    return new Vector($x);
}

function vec2(float $x = 0, float $y = 0): Vector2
{
    return new Vector2($x, $y);
}

function vec3(float $x = 0, float $y = 0, float $z = 0): Vector3
{
    return new Vector3($x, $y, $z);
}

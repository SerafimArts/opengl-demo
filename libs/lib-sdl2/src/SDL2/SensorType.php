<?php

declare(strict_types=1);

namespace Bic\Lib\SDL2;

interface SensorType
{
    public const SDL_SENSOR_INVALID = -1;
    public const SDL_SENSOR_UNKNOWN = 0;
    public const SDL_SENSOR_ACCEL   = 1;
    public const SDL_SENSOR_GYRO    = 2;
}

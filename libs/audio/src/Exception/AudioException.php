<?php

declare(strict_types=1);

namespace Bic\Audio\Exception;

use Bic\Lib\BassAL;

class AudioException extends \Exception
{
    /**
     * @param int $code
     * @return self
     */
    public static function fromErrorCode(int $code): self
    {
        return new self(match ($code) {
            BassAL::BASS_ERROR_DEVICE => 'Invalid device',
            BassAL::BASS_ERROR_NOTAVAIL => 'Please use the real device instead',
            BassAL::BASS_ERROR_ALREADY => 'The device has already been initialized',
            BassAL::BASS_ERROR_ILLPARAM => 'Not valid window handle',
            BassAL::BASS_ERROR_DRIVER => 'There is no available device driver',
            BassAL::BASS_ERROR_BUSY => 'Something else has exclusive use of the device',
            BassAL::BASS_ERROR_FORMAT => 'The specified format is not supported by the device',
            BassAL::BASS_ERROR_MEM => 'There is insufficient memory',
            BassAL::BASS_ERROR_UNKNOWN => 'Some other mystery problem!',
            default => 'Unknown error #' . $code,
        }, $code);
    }
}

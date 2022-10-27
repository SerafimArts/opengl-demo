<?php

declare(strict_types=1);

namespace Bic\Image\Exception;

class ImageException extends \Exception
{
    final public function __construct(string $message, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

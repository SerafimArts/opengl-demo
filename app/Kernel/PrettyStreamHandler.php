<?php

declare(strict_types=1);

namespace App\Kernel;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler as BaseStreamHandler;
use Monolog\Level;

final class PrettyStreamHandler extends BaseStreamHandler
{
    public function __construct(
        $stream,
        int|string|Level $level = Level::Debug,
        bool $bubble = true,
        ?int $filePermission = null,
        bool $useLocking = false
    ) {
        parent::__construct($stream, $level, $bubble, $filePermission, $useLocking);

        $formatter = $this->getFormatter();
        if ($formatter instanceof LineFormatter) {
            $formatter->allowInlineLineBreaks();
        }
    }
}

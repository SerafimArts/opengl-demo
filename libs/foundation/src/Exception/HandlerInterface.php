<?php

declare(strict_types=1);

namespace Bic\Foundation\Exception;

\defined('SIG_ERR') or \define('SIG_ERR', -1);

interface HandlerInterface
{
    /**
     * @var positive-int
     */
    final public const ERROR_CODE = \SIG_ERR;

    /**
     * @param \Throwable $e
     * @return int
     */
    public function throw(\Throwable $e): int;
}

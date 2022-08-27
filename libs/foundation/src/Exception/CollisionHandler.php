<?php

declare(strict_types=1);

namespace Bic\Foundation\Exception;

use NunoMaduro\Collision\Writer;
use Whoops\Exception\Inspector;

class CollisionHandler implements HandlerInterface
{
    /**
     * @var Writer
     */
    private readonly Writer $writer;

    public function __construct()
    {
        $this->writer = new Writer();
    }

    /**
     * {@inheritDoc}
     */
    public function throw(\Throwable $e): int
    {
        $this->writer->write(new Inspector($e));

        return $e->getCode() ?: self::ERROR_CODE;
    }
}

<?php

declare(strict_types=1);

namespace Bic\Foundation\Exception;

final class Factory implements HandlerInterface
{
    /**
     * @return HandlerInterface
     */
    private function instance(): HandlerInterface
    {
        if (\class_exists(\NunoMaduro\Collision\Writer::class)) {
            return new CollisionHandler();
        }

        return new ConsoleHandler();
    }

    /**
     * {@inheritDoc}
     */
    public function throw(\Throwable $e): int
    {
        $handler = $this->instance();

        return $handler->throw($e);
    }
}

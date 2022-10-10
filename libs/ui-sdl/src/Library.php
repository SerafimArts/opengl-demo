<?php

declare(strict_types=1);

namespace Bic\UI\SDL;

use FFI\Env\Runtime;
use FFI\Location\Locator;
use FFI\Proxy\Proxy;

/**
 * @internal This is an internal library interface, please do not use it in your code.
 * @psalm-internal Bic\UI\SDL
 */
final class Library extends Proxy
{
    /**
     * @psalm-taint-sink file $library
     * @param non-empty-string|null $library
     */
    public function __construct(string $library = null)
    {
        Runtime::assertAvailable();

        parent::__construct(\FFI::cdef(
            \file_get_contents(__DIR__ . '/../resources/sdl-2.0.min.h'),
            $library ?? $this->getLibrary(),
        ));
    }

    /**
     * @return non-empty-string
     */
    private function getLibrary(): string
    {
        return match (\PHP_OS_FAMILY) {
            'Windows' => Locator::pathname('SDL2.dll'),
            'Linux', 'BSD' => Locator::pathname('libSDL2-2.0.so.0'),
            'Darwin' => Locator::pathname('libSDL2-2.0.0.dylib'),
            default => null,
        } ?? throw new \LogicException('Could not find any available SDL2 library');
    }
}

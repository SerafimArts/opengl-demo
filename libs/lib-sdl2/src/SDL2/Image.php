<?php

/**
 * This file is part of Bic Engine package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Bic\Lib\SDL2;

use Bic\Lib\SDL2;
use Bic\Lib\SDL2\Exception\LoadingException;
use Bic\Lib\SDL2\Exception\PlatformException;
use FFI\Contracts\Preprocessor\Exception\DirectiveDefinitionExceptionInterface;
use FFI\Env\Exception\EnvironmentException;
use FFI\Env\Runtime;
use FFI\Headers\SDL2Image as SDL2ImageHeaders;
use FFI\Headers\SDL2Image\Version as HeadersVersion;
use FFI\Location\Locator;
use FFI\Proxy\Proxy;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;

final class Image extends Proxy
{
    /**
     * @var non-empty-string
     */
    public readonly string $version;

    /**
     * @var non-empty-string
     */
    public readonly string $binary;

    /**
     * @psalm-taint-sink file $binary
     * @param SDL2 $sdl2
     * @param non-empty-string|null $binary
     * @param non-empty-string|null $version
     * @param CacheInterface|null $cache
     * @throws DirectiveDefinitionExceptionInterface
     * @throws InvalidArgumentException
     * @psalm-suppress MixedArgument
     * @psalm-suppress InvalidThrow
     */
    public function __construct(
        ?string $binary = null,
        ?string $version = null,
        private readonly ?CacheInterface $cache = null,
    ) {
        assert(Runtime::assertAvailable(), EnvironmentException::getErrorMessageFromStatus());

        $this->binary = $binary ?? $this->detectBinary();
        $this->version = $version ?? $this->detectVersion($this->binary);

        parent::__construct(\FFI::cdef(
            $this->getCachedHeaders(),
            $this->binary,
        ));
    }

    /**
     * @return non-empty-string
     *
     * @psalm-suppress LessSpecificReturnStatement
     * @psalm-suppress MoreSpecificReturnType
     */
    private function detectBinary(): string
    {
        return match (\PHP_OS_FAMILY) {
            'Windows' => Locator::resolve('SDL2_image.dll')
                ?? throw new LoadingException(<<<'error'
                Windows OS requires a reference to SDL2 Image (SDL2_image.dll) library.
                Please download the required version of the library from
                the official repository: https://github.com/libsdl-org/SDL_image/releases
                error),
            'Linux', 'BSD', 'Solaris' => Locator::resolve('libSDL2_image-2.0.so.0')
                ?? throw new LoadingException(<<<'error'
                Could not load SDL2 Image (libSDL2_image-2.0.so.0) library.
                Please download the required version of the library from
                the official repository: https://github.com/libsdl-org/SDL_image/releases
                error),
            'Darwin' => Locator::resolve('libSDL2_image-2.0.0.dylib')
                ?? throw new LoadingException(<<<'error'
                Could not load SDL2 Image (libSDL2_image-2.0.0.dylib) library.
                Please download the required version of the library from
                the official repository: https://github.com/libsdl-org/SDL_image/releases
                error),
            default => throw new PlatformException(
                'Could not detect library for ' . \PHP_OS
            )
        };
    }

    /**
     * @param non-empty-string $binary
     * @return non-empty-string
     * @psalm-suppress MixedMethodCall
     */
    private function detectVersion(string $binary): string
    {
        /** @var \FFI|object $ffi */
        $ffi = \FFI::cdef(<<<'CLANG'
        typedef struct SDL_version {
            uint8_t major;
            uint8_t minor;
            uint8_t patch;
        } SDL_version;
        extern const SDL_version* IMG_Linked_Version(void);
        CLANG, $binary);

        /** @var object{major:int, minor:int, patch:int} $version */
        $version = $ffi->IMG_Linked_Version();

        /** @var non-empty-string */
        return \vsprintf('%d.%d.%d', [
            $version->major,
            $version->minor,
            $version->patch,
        ]);
    }

    /**
     * @return non-empty-string
     * @throws DirectiveDefinitionExceptionInterface
     * @throws InvalidArgumentException
     *
     * @psalm-suppress InvalidThrow
     * @psalm-suppress LessSpecificReturnStatement
     * @psalm-suppress MoreSpecificReturnType
     */
    private function getCachedHeaders(): string
    {
        $headers = SDL2ImageHeaders::create(
            version: HeadersVersion::create($this->version),
        );

        if ($this->cache === null) {
            return (string)$headers;
        }

        $key = \hash('xxh128', \sprintf('sdl-image-%s', $this->version));

        try {
            if (!$this->cache->has($key)) {
                $this->cache->set($key, (string)$headers);
            }

            return (string)$this->cache->get($key);
        } catch (\Throwable) {
            return (string)$headers;
        }
    }
}

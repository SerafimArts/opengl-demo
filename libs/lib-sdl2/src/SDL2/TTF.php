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
use FFI\Headers\SDL2TTF as SDL2TTFHeaders;
use FFI\Headers\SDL2TTF\Version as HeadersVersion;
use FFI\Location\Locator;
use FFI\Proxy\Proxy;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;

final class TTF extends Proxy
{
    public const UNICODE_BOM_NATIVE  = 0xFEFF;
    public const UNICODE_BOM_SWAPPED = 0xFFFE;

    public const TTF_STYLE_NORMAL        = 0x00;
    public const TTF_STYLE_BOLD          = 0x01;
    public const TTF_STYLE_ITALIC        = 0x02;
    public const TTF_STYLE_UNDERLINE     = 0x04;
    public const TTF_STYLE_STRIKETHROUGH = 0x08;

    public const TTF_HINTING_NORMAL          = 0;
    public const TTF_HINTING_LIGHT           = 1;
    public const TTF_HINTING_MONO            = 2;
    public const TTF_HINTING_NONE            = 3;
    public const TTF_HINTING_LIGHT_SUBPIXEL  = 4;

    public const TTF_WRAPPED_ALIGN_LEFT      = 0;
    public const TTF_WRAPPED_ALIGN_CENTER    = 1;
    public const TTF_WRAPPED_ALIGN_RIGHT     = 2;

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
        private readonly SDL2 $sdl2,
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
            'Windows' => Locator::resolve('SDL2_ttf.dll')
                ?? throw new LoadingException(<<<'error'
                Windows OS requires a reference to SDL2 TTF (SDL2_ttf.dll) library.
                Please download the required version of the library from
                the official repository: https://github.com/libsdl-org/SDL_ttf/releases
                error),
            'Linux', 'BSD', 'Solaris' => Locator::resolve('libSDL2_ttf-2.0.so.0')
                ?? throw new LoadingException(<<<'error'
                Could not load SDL2 TTF (libSDL2_ttf-2.0.so.0) library.
                Please download the required version of the library from
                the official repository: https://github.com/libsdl-org/SDL_ttf/releases
                error),
            'Darwin' => Locator::resolve('libSDL2_ttf-2.0.0.dylib')
                ?? throw new LoadingException(<<<'error'
                Could not load SDL2 TTF (libSDL2_ttf-2.0.0.dylib) library.
                Please download the required version of the library from
                the official repository: https://github.com/libsdl-org/SDL_ttf/releases
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
        extern const SDL_version* TTF_Linked_Version(void);
        CLANG, $binary);

        /** @var object{major:int, minor:int, patch:int} $version */
        $version = $ffi->TTF_Linked_Version();

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
        $headers = SDL2TTFHeaders::create(
            version: HeadersVersion::create($this->version),
        );

        if ($this->cache === null) {
            return (string)$headers;
        }

        $key = \hash('xxh128', \sprintf('sdl-ttf-%s', $this->version));

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

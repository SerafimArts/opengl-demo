<?php

declare(strict_types=1);

namespace Bic\Lib\BassAL;

use Bic\Lib\BassAL\Exception\LoadingException;
use Bic\Lib\BassAL\Exception\PlatformException;
use FFI\Contracts\Headers\HeaderInterface;
use FFI\Contracts\Preprocessor\Exception\DirectiveDefinitionExceptionInterface;
use FFI\Env\Exception\EnvironmentException;
use FFI\Env\Runtime;
use FFI\Location\Locator;
use FFI\Proxy\Proxy;
use FFI\Headers\BassMix as MixHeaders;
use FFI\Headers\BassMix\Platform as MixPlatform;
use FFI\Headers\BassMix\Version as MixVersion;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;

final class Mixer extends Proxy
{
    /**
     * @var non-empty-string
     */
    public readonly string $binary;

    /**
     * @var non-empty-string
     */
    public readonly string $version;

    /**
     * @param non-empty-string|null $binary
     * @param non-empty-string|null $version
     * @param Platform|null $platform
     * @param CacheInterface|null $cache
     * @throws DirectiveDefinitionExceptionInterface
     */
    public function __construct(
        ?string $binary = null,
        ?string $version = null,
        public readonly ?Platform $platform = null,
        private readonly ?CacheInterface $cache = null,
    ) {
        assert(Runtime::assertAvailable(), EnvironmentException::getErrorMessageFromStatus());

        $this->binary = $binary ?? $this->detectBinary();
        $this->version = $version ?? $this->detectVersion($this->binary);

        parent::__construct(\FFI::cdef(
            $this->getCachedHeaders($this->platform),
            $this->binary,
        ));
    }

    /**
     * @psalm-taint-sink file $binary
     * @param non-empty-string $binary
     *
     * @return non-empty-string
     */
    private function detectVersion(string $binary): string
    {
        $ffi = \FFI::cdef(<<<'CDATA'
        typedef unsigned long DWORD;
        DWORD BASS_Mixer_GetVersion(void);
        CDATA, $binary);

        $version = $ffi->BASS_Mixer_GetVersion();

        return \vsprintf('%d.%d', [
            ($version & 0xff000000) >> 24,
            ($version & 0x00ff0000) >> 16
        ]);
    }

    /**
     * @param Platform|null $platform
     *
     * @return non-empty-string
     *
     * @throws DirectiveDefinitionExceptionInterface
     * @throws InvalidArgumentException
     * @psalm-suppress InvalidThrow
     * @psalm-suppress MoreSpecificReturnType
     * @psalm-suppress LessSpecificReturnStatement
     */
    private function getCachedHeaders(?Platform $platform): string
    {
        $headers = $this->getHeaders($platform);

        if ($this->cache === null) {
            return (string)$headers;
        }

        $key = $this->getCachedHeadersKey($platform);

        try {
            if (!$this->cache->has($key)) {
                $this->cache->set($key, $headers = (string)$headers);

                return $headers;
            }

            return (string)$this->cache->get($key);
        } catch (\Throwable) {
            return (string)$headers;
        }
    }

    /**
     * @param Platform|null $platform
     * @return string
     */
    private function getCachedHeadersKey(?Platform $platform): string
    {
        return \hash('xxh128', \vsprintf('bass-mix-%s|%s', [
            $this->version,
            $platform?->name ?? '<unknown>',
        ]));
    }

    /**
     * @param Platform|null $platform
     *
     * @return HeaderInterface
     * @throws DirectiveDefinitionExceptionInterface
     */
    private function getHeaders(?Platform $platform): HeaderInterface
    {
        return MixHeaders::create(
            platform: match ($platform) {
                Platform::WINDOWS => MixPlatform::WINDOWS,
                Platform::LINUX => MixPlatform::LINUX,
                Platform::DARWIN => MixPlatform::DARWIN,
                default => null,
            },
            version: MixVersion::create($this->version),
        );
    }

    /**
     * @return non-empty-string
     * @psalm-suppress MoreSpecificReturnType
     * @psalm-suppress LessSpecificReturnStatement
     */
    private function detectBinary(): string
    {
        return match (\PHP_OS_FAMILY) {
            'Windows' => Locator::resolve('bassmix.dll')
                ?? throw new LoadingException(<<<'error'
                Windows OS requires a reference to BassAL Mixer (bassmix.dll) library.
                Please download the required version of the library from
                the official repository: https://www.un4seen.com/
                error),
            'Linux', 'BSD', 'Solaris' => Locator::resolve('libbassmix.so')
                ?? throw new LoadingException(<<<'error'
                Could not load BassAL Mixer (libbassmix.so) library.
                Please download the required version of the library from
                the official repository: https://www.un4seen.com/
                error),
            'Darwin' => Locator::resolve('libbassmix.dylib')
                ?? throw new LoadingException(<<<'error'
                Could not load BassAL Mixer (libbassmix.dylib) library.
                Please download the required version of the library from
                the official repository: https://www.un4seen.com/
                error),
            default => throw new PlatformException(
                'Could not detect library for ' . \PHP_OS
            )
        };
    }
}

<?php

declare(strict_types=1);

namespace Bic\Audio;

use Bic\Audio\Channel\Channel;
use Bic\Audio\Exception\AudioException;
use Bic\Audio\Repository\FileSource;
use Bic\Audio\Repository\FileSourceInterface;
use Bic\Audio\Repository\SourceInterface;
use Bic\Lib\BassAL;

final class Repository implements RepositoryInterface
{
    /**
     * @var \WeakMap<SourceInterface, object>
     */
    private readonly \WeakMap $streams;

    /**
     * @param BassAL $bass
     */
    public function __construct(
        private readonly BassAL $bass,
    ) {
        $this->streams = new \WeakMap();
    }

    /**
     * @template TSource of SourceInterface
     * @param TSource $source
     * @param \Closure():void $then
     * @return TSource
     */
    private function withDestructCallback(SourceInterface $source, \Closure $then): SourceInterface
    {
        $this->streams[$source] ??= new class ($then) {
            /**
             * @param \Closure():void $destructor
             */
            public function __construct(
                private readonly \Closure $destructor,
            ) {
            }

            /**
             * @return void
             */
            public function __destruct()
            {
                ($this->destructor)();
            }
        };

        return $source;
    }

    /**
     * {@inheritDoc}
     *
     * @psalm-suppress all
     */
    public function loadFromPathname(string $pathname, int $offset = 0, int $length = null): FileSourceInterface
    {
        $pathname = \realpath($pathname) ?: throw new AudioException(
            \sprintf('File "%s" not found', $pathname)
        );

        $flags = Channel::SAMPLE_FLAGS | BassAL::BASS_STREAM_DECODE;
        $id = $this->bass->BASS_StreamCreateFile(0, $pathname, $offset, (int)$length, $flags);

        return $this->withDestructCallback(new FileSource($id, $pathname), function () use ($id): void {
            $this->bass->BASS_StreamFree($id);
        });
    }
}

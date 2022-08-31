<?php

declare(strict_types=1);

namespace Bic\Tiled\Common;

/**
 * @psalm-consistent-constructor
 */
class Version implements \Stringable
{
    /**
     * @psalm-param positive-int|0 $major
     * @psalm-param positive-int|0 $minor
     * @psalm-param positive-int|0 $patch
     */
    public function __construct(
        public int $major = 1,
        public int $minor = 0,
        public int $patch = 0,
    ) {
    }

    /**
     * @param non-empty-string $version
     * @return static
     */
    public static function fromString(string $version): static
    {
        $chunks = \explode('.', $version);

        return new static(
            (int)($chunks[0] ?? 1),
            (int)($chunks[1] ?? 0),
            (int)($chunks[2] ?? 0),
        );
    }

    /**
     * @return non-empty-string
     */
    public function toMajorString(): string
    {
        return (string)$this->major;
    }

    /**
     * @return non-empty-string
     */
    public function toMajorMinorString(): string
    {
        return \sprintf('%d.%d', $this->major, $this->minor);
    }

    /**
     * @return non-empty-string
     */
    public function toMajorMinorPatchString(): string
    {
        return \sprintf('%d.%d.%d', $this->major, $this->minor, $this->patch);
    }

    /**
     * @return non-empty-string
     */
    public function __toString(): string
    {
        if ($this->patch === 0) {
            return $this->toMajorMinorString();
        }

        return $this->toMajorMinorPatchString();
    }
}

<?php

declare(strict_types=1);

namespace Serafim\Bic\Progress;

class Loading implements LoadingInterface
{
    /**
     * @var \Generator
     */
    private \Generator $stream;

    /**
     * @param \Generator $stream
     */
    public function __construct(\Generator $stream)
    {
        $this->stream = $stream;
    }

    /**
     * @param \Closure $closure
     * @return LoadingInterface
     */
    public static function fromClosure(\Closure $closure): LoadingInterface
    {
        return new static($closure());
    }

    /**
     * @return string|null
     */
    public function next(): ?string
    {
        if ($this->stream->valid()) {
            try {
                return $this->stream->current();
            } finally {
                $this->stream->next();
            }
        }

        return null;
    }

    /**
     * @return bool
     */
    public function isCompleted(): bool
    {
        return ! $this->stream->valid();
    }

    /**
     * @return mixed
     */
    public function result()
    {
        if ($this->stream->valid()) {
            \iterator_count($this->stream);
        }

        return $this->stream->getReturn();
    }
}

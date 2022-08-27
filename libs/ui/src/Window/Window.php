<?php

declare(strict_types=1);

namespace Bic\UI\Window;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

abstract class Window implements WindowInterface
{
    /**
     * @var UuidInterface
     */
    protected readonly UuidInterface $id;

    /**
     * @var bool
     */
    protected bool $closed = false;

    /**
     * @param \Closure(WindowInterface):void $detach
     */
    protected function __construct(
        private readonly \Closure $detach,
    ) {
        $this->id = Uuid::uuid4();
    }

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * {@inheritDoc}
     */
    public function close(): void
    {
        $this->closed = true;
        ($this->detach)($this);
    }

    /**
     * {@inheritDoc}
     */
    public function isClosed(): bool
    {
        return $this->closed;
    }

    /**
     * @return void
     */
    public function __destruct()
    {
        if (!$this->closed) {
            $this->close();
        }
    }

    /**
     * @return array{ id: non-empty-string }
     */
    final public function __debugInfo(): array
    {
        return ['id' => $this->id->toString()];
    }
}

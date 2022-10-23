<?php

declare(strict_types=1);

namespace Bic\UI\SDL;

use Bic\UI\Position;
use Bic\UI\SDL\Internal\HandlerFactory;
use Bic\UI\Size;
use Bic\UI\Window\CreateInfo;
use Bic\UI\Window\HandleInterface;
use Bic\UI\Window\WindowInterface;
use FFI\CData;
use JetBrains\PhpStorm\Immutable;

/**
 * @psalm-suppress all
 *
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Bic\UI\SDL
 */
final class Window implements WindowInterface
{
    /**
     * The title of the window, in UTF-8 encoding.
     *
     * @var string
     */
    public readonly string $title;

    /**
     * The size of the window, in screen coordinates.
     *
     * @var Size
     */
    public readonly Size $size;

    /**
     * The X (left) and Y (top) position of the window.
     *
     * @var Position
     */
    public readonly Position $position;

    /**
     * @var bool
     * @readonly
     * @psalm-readonly-allow-private-mutation
     */
    #[Immutable(allowedWriteScope: Immutable::PRIVATE_WRITE_SCOPE)]
    public bool $closed = false;

    /**
     * @var HandleInterface|null
     */
    private ?HandleInterface $handle = null;

    /**
     * @param object $sdl
     * @param CData $ptr
     * @param CreateInfo $info
     * @param \Closure($this):void $close
     */
    public function __construct(
        private readonly object $sdl,
        private readonly CData $ptr,
        CreateInfo $info,
        private readonly \Closure $close
    ) {
        [$x, $y] = [$this->sdl->new('int'), $this->sdl->new('int')];

        $this->sdl->SDL_GetWindowPosition($this->ptr, \FFI::addr($x), \FFI::addr($y));

        $this->title = $info->title;
        $this->size = new Size($info->size->width, $info->size->height);
        $this->position = new Position($x->cdata, $y->cdata);
    }

    /**
     * {@inheritDoc}
     */
    public function getHandle(): HandleInterface
    {
        if ($this->closed) {
            throw new \LogicException('Window already has been closed');
        }

        if ($this->handle === null) {
            $factory = new HandlerFactory($this->sdl);

            return $this->handle = $factory->get($this->ptr);
        }

        return $this->handle;
    }

    /**
     * {@inheritDoc}
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * {@inheritDoc}
     */
    public function getSize(): Size
    {
        return $this->size;
    }

    /**
     * {@inheritDoc}
     */
    public function getPosition(): Position
    {
        return $this->position;
    }

    /**
     * {@inheritDoc}
     */
    public function isClosed(): bool
    {
        return $this->closed;
    }

    /**
     * @return CData
     */
    public function getCData(): CData
    {
        return $this->ptr;
    }

    /**
     * {@inheritDoc}
     */
    public function show(): void
    {
        if ($this->closed) {
            throw new \LogicException('Window already has been closed');
        }

        $this->sdl->SDL_ShowWindow($this->ptr);
    }

    /**
     * {@inheritDoc}
     */
    public function hide(): void
    {
        if ($this->closed) {
            throw new \LogicException('Window already has been closed');
        }

        $this->sdl->SDL_HideWindow($this->ptr);
    }

    /**
     * {@inheritDoc}
     */
    public function close(): void
    {
        if ($this->closed === false) {
            $this->closed = true;
            ($this->close)($this);
            $this->sdl->SDL_DestroyWindow($this->ptr);
        }
    }
}

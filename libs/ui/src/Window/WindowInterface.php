<?php

declare(strict_types=1);

namespace Bic\UI\Window;

use Bic\UI\Position;
use Bic\UI\Size;

interface WindowInterface
{
    /**
     * Returns the title of the window in UTF-8 format or "" if there is no title.
     *
     * @return string
     */
    public function getTitle(): string;

    /**
     * Returns the size of a window's client area.
     *
     * @return Size
     */
    public function getSize(): Size;

    /**
     * Returns the position of a window.
     *
     * @return Position
     */
    public function getPosition(): Position;

    /**
     * Returns native (OS-dependent) window handle.
     *
     * @return HandleInterface
     */
    public function getHandle(): HandleInterface;

    /**
     * @return void
     */
    public function show(): void;

    /**
     * @return void
     */
    public function hide(): void;

    /**
     * @return void
     */
    public function close(): void;

    /**
     * @return bool
     */
    public function isClosed(): bool;
}

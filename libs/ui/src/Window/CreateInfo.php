<?php

declare(strict_types=1);

namespace Bic\UI\Window;

use Bic\UI\Position;
use Bic\UI\Size;

class CreateInfo
{
    /**
     * Default window width value in screen coordinates.
     */
    public const DEFAULT_WIDTH = 640;

    /**
     * Default window height value in screen coordinates.
     */
    public const DEFAULT_HEIGHT = 480;

    /**
     * @param string $title The title of the window, in UTF-8 encoding.
     * @param Size $size The size of the window, in screen coordinates.
     * @param Position|null $position The X (left) and Y (top) position of the
     *        window or center of the screen if {@see null} is provided.
     * @param Mode $mode Visibility mode of the new window.
     * @param bool $closable Allow window closing
     */
    public function __construct(
        public readonly string $title = '',
        public readonly Size $size = new Size(self::DEFAULT_WIDTH, self::DEFAULT_HEIGHT),
        public readonly ?Position $position = null,
        public readonly Mode $mode = Mode::NORMAL,
        public readonly bool $closable = true,
    ) {
    }
}

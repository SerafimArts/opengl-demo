<?php

declare(strict_types=1);

namespace Bic\UI\Window;

enum Mode
{
    /**
     * Default behavior of the window
     */
    case NORMAL;

    /**
     * The window is hidden (minimized)
     */
    case HIDDEN;

    /**
     * The window is expanded to fullscreen (priority mode with
     * graphics acceleration).
     */
    case FULLSCREEN;

    /**
     * The window is expanded to desktop fullscreen.
     */
    case DESKTOP_FULLSCREEN;
}

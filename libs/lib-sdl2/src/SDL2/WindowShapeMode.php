<?php

declare(strict_types=1);

namespace Bic\Lib\SDL2;

interface WindowShapeMode
{
    /**
     * @deprecated please use {@see SHAPE_MODE_DEFAULT} instead.
     */
    public const ShapeModeDefault = 0;

    /**
     * @deprecated please use {@see SHAPE_MODE_BINARIZE_ALPHA} instead.
     */
    public const ShapeModeBinarizeAlpha = 1;

    /**
     * @deprecated please use {@see SHAPE_MODE_REVERSE_BINARIZE_ALPHA} instead.
     */
    public const ShapeModeReverseBinarizeAlpha = 2;

    /**
     * @deprecated please use {@see SHAPE_MODE_COLOR_KEY} instead.
     */
    public const ShapeModeColorKey = 3;

    public const SHAPE_MODE_DEFAULT                = self::ShapeModeDefault;
    public const SHAPE_MODE_BINARIZE_ALPHA         = self::ShapeModeBinarizeAlpha;
    public const SHAPE_MODE_REVERSE_BINARIZE_ALPHA = self::ShapeModeReverseBinarizeAlpha;
    public const SHAPE_MODE_COLOR_KEY              = self::ShapeModeColorKey;
}

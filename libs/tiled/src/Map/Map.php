<?php

declare(strict_types=1);

namespace Bic\Tiled\Map;

use Bic\Tiled\Common\Version;

abstract class Map
{
    /**
     * @var BackgroundColor|null
     */
    public ?BackgroundColor $background = null;

    /**
     * The class of the map
     *
     * @since 1.9
     * @var string|null
     */
    public ?string $class = null;

    /**
     * The compression level to use for tile layer data.
     *
     * @var int
     */
    public int $compressionLevel = -1;

    /**
     * Number of tile columns (width) / rows (height).
     *
     * @var MapSize
     */
    public MapSize $size;

    /**
     * Map grid width/height.
     *
     * @var TileSize
     */
    public TileSize $tiles;

    /**
     * Whether the map has infinite dimensions.
     *
     * @var bool
     */
    public bool $infinite = false;

    /**
     * Auto-increments for each layer.
     *
     * @var positive-int|0
     */
    public int $nextLayerID = 0;

    /**
     * Auto-increments for each placed object.
     *
     * @var positive-int|0
     */
    public int $nextObjectID = 0;

    /**
     * X/Y coordinate of the parallax origin in pixels.
     *
     * @since 1.8
     * @var ParallaxOrigin
     */
    public ParallaxOrigin $parallax;

    /**
     * The Tiled version used to save the file.
     *
     * @var TiledVersion
     */
    public TiledVersion $tiled;

    /**
     * @since 1.0
     * @var Type
     */
    public Type $type = Type::MAP;

    /**
     * The JSON format version.
     *
     * @var Version
     */
    public Version $version;

    /**
     * Array of Layers
     *
     * @var array
     */
    public array $layers = [];

    /**
     * Array of Properties
     *
     * @var array
     */
    public array $properties = [];

    /**
     * Array of Tilesets.
     *
     * @var array
     */
    public array $tilesets = [];

    public function __construct()
    {
        $this->size = new MapSize();
        $this->tiles = new TileSize();
        $this->parallax = new ParallaxOrigin();
        $this->tiled = TiledVersion::latest();
        $this->version = new Version();
    }
}

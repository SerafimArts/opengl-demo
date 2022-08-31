<?php

declare(strict_types=1);

namespace Bic\Tiled\Loader;

use Bic\Tiled\Common\Color;
use Bic\Tiled\Common\Version;
use Bic\Tiled\HexagonalMap;
use Bic\Tiled\IsometricMap;
use Bic\Tiled\Map\BackgroundColor;
use Bic\Tiled\Map\Map;
use Bic\Tiled\Map\MapSize;
use Bic\Tiled\Map\ParallaxOrigin;
use Bic\Tiled\Map\PerspectiveMap;
use Bic\Tiled\Map\Property\BoolProperty;
use Bic\Tiled\Map\Property\ClassProperty;
use Bic\Tiled\Map\Property\ColorProperty;
use Bic\Tiled\Map\Property\CustomProperty;
use Bic\Tiled\Map\Property\FileProperty;
use Bic\Tiled\Map\Property\FloatProperty;
use Bic\Tiled\Map\Property\IntProperty;
use Bic\Tiled\Map\Property\ObjectProperty;
use Bic\Tiled\Map\Property\Property;
use Bic\Tiled\Map\Property\StringProperty;
use Bic\Tiled\Map\RenderOrder;
use Bic\Tiled\Map\StaggerAxis;
use Bic\Tiled\Map\StaggerIndex;
use Bic\Tiled\Map\TiledVersion;
use Bic\Tiled\Map\TileSize;
use Bic\Tiled\Map\Type;
use Bic\Tiled\OrthogonalMap;
use Bic\Tiled\StaggeredMap;

/**
 * @psalm-type MapOrientationType = "orthogonal" | "isometric" | "staggered" | "hexagonal"
 * @psalm-type PropertyBuiltinType = "string" | "int" | "float" | "bool" | "color" | "file" | "object"
 * @psalm-type PropertyType = array{
 *  name: non-empty-string,
 *  type?: PropertyBuiltinType,
 *  propertytype?: non-empty-string,
 *  value: mixed,
 * }
 */
abstract class Loader implements LoaderInterface
{
    /**
     * @param array{orientation: string} $data
     * @psalm-param array{orientation: MapOrientationType} $data
     * @return Map
     */
    private function createMapInstance(array $data): Map
    {
        return new (match ($data['orientation'] ?? '') {
            'orthogonal' => OrthogonalMap::class,
            'isometric' => IsometricMap::class,
            'staggered ' => StaggeredMap::class,
            'hexagonal' => HexagonalMap::class,
            default => throw new \InvalidArgumentException(
                'Invalid or unsupported map orientation format'
            ),
        });
    }

    /**
     * @psalm-param PropertyType $data
     * @param array $data
     * @return Property
     */
    protected function createPropertyInstance(array $data): Property
    {
        if (!isset($data['name'])) {
            throw new \InvalidArgumentException('Property name required');
        }

        [$name, $value] = [$data['name'], $data['value'] ?? null];

        if (isset($data['propertytype'])) {
            return new CustomProperty($name, $value ?? '', $data['propertytype']);
        }

        return match ($data['type'] ?? 'string') {
            'string' => new StringProperty($name, (string)$value),
            'int' => new IntProperty($name, (int)$value),
            'float' => new FloatProperty($name, (float)$value),
            'bool' => new BoolProperty($name, (bool)(int)$value),
            'color' => new ColorProperty($name, Color::fromHexString((string)$value)),
            'file' => new FileProperty($name, (string)$value),
            'object' => new ObjectProperty($name, (object)$value),
            'class' => new ClassProperty($name, (string)$value),
            default => throw new \InvalidArgumentException(
                \sprintf('Invalid or unsupported property type format "%s"', $data['type'])
            ),
        };
    }

    /**
     * @psalm-param list<PropertyType> $properties
     * @param array<array> $properties
     * @return array<Property>
     */
    protected function createPropertyInstances(iterable $properties): array
    {
        $result = [];

        foreach ($properties as $property) {
            $result[] = $this->createPropertyInstance($property);
        }

        return $result;
    }

    /**
     * @param array $data
     * @return Map
     */
    protected function mapFromArray(array $data): Map
    {
        $instance = $this->createMapInstance($data);

        if (isset($data['backgroundcolor'])) {
            $instance->background = BackgroundColor::fromHexString($data['backgroundcolor']);
        }

        if (isset($data['width']) || isset($data['height'])) {
            $instance->size = new MapSize(
                (int)($data['width'] ?? 0),
                (int)($data['height'] ?? 0),
            );
        }

        if (isset($data['tilewidth']) || isset($data['tileheight'])) {
            $instance->tiles = new TileSize(
                (int)($data['tilewidth'] ?? 0),
                (int)($data['tileheight'] ?? 0),
            );
        }

        if (isset($data['parallaxoriginx']) || isset($data['parallaxoriginy'])) {
            $instance->parallax = new ParallaxOrigin(
                (float)($data['parallaxoriginx'] ?? 0.0),
                (float)($data['parallaxoriginy'] ?? 0.0),
            );
        }

        if (isset($data['tiledversion'])) {
            $instance->tiled = TiledVersion::fromString($data['tiledversion']);
        }

        if (isset($data['version'])) {
            $instance->version = Version::fromString((string)$data['version']);
        }

        $instance->class = $data['class'] ?? null;
        $instance->compressionLevel = (int)($data['compressionlevel'] ?? -1);
        $instance->infinite = (bool)(int)($data['infinite'] ?? false);
        $instance->nextLayerID = (int)($data['nextlayerid'] ?? 0);
        $instance->nextObjectID = (int)($data['nextobjectid'] ?? 0);

        $instance->type = Type::from(
            $data['type'] ?? 'map'
        );

        if ($instance instanceof HexagonalMap) {
            $instance->hexSideLength = $data['hexsidelength'] ?? 0;
        }

        if ($instance instanceof PerspectiveMap) {
            $instance->staggerAxis = StaggerAxis::from(
                $data['staggeraxis'] ?? 'x'
            );
            $instance->staggerIndex = StaggerIndex::from(
                $data['staggerindex'] ?? 'odd'
            );
        }

        if ($instance instanceof OrthogonalMap) {
            $instance->renderOrder = RenderOrder::from(
                $data['renderorder'] ?? 'right-down'
            );
        }

        $instance->properties = $this->createPropertyInstances(
            $data['properties'] ?? []
        );

        // $instance->layers = $data['layers'] ?? [];
        // $instance->tilesets = $data['tilesets'] ?? [];

        return $instance;
    }
}

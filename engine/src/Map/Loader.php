<?php

declare(strict_types=1);

namespace Serafim\Bic\Map;

use Serafim\Bic\Lifecycle\Lifecycle;
use Serafim\Bic\Progress\Loading;
use Serafim\Bic\Progress\LoadingInterface;
use Serafim\Bic\Util;

class Loader
{
    /**
     * @var Lifecycle
     */
    private Lifecycle $game;

    /**
     * @param Lifecycle $game
     */
    public function __construct(Lifecycle $game)
    {
        $this->game = $game;
    }

    /**
     * @param Data $data
     * @return LoadingInterface
     */
    public function load(Data $data): LoadingInterface
    {
        return Loading::fromClosure(function() use ($data) {
            $progress = $this->progress($data);

            foreach ($progress as $name => $item) {
                yield $name;
            }

            return $progress->getReturn();
        });
    }

    /**
     * @param Data $data
     * @return \Generator|Map
     */
    private function progress(Data $data)
    {
        $map = new Map($this->game);

        foreach ($data->get('layers') as $id => $info) {
            yield $info['name'] => $layer = $this->loadLayer($info);

            foreach ((array)$data->get('layers.' . $id . '.data', []) as $index => $tileId) {
                if ($tile = $this->loadTile($layer, $data, $index, $tileId)) {
                    $layer->addTile($tile);
                }
            }

            $map->addLayer($layer);
        }

        return $map;
    }

    /**
     * @param Layer $layer
     * @param Data $data
     * @param int $index
     * @param int $tileId
     * @return Tile|null
     */
    private function loadTile(Layer $layer, Data $data, int $index, int $tileId): ?Tile
    {
        if (($tileInfo = $this->getTileInfo($data, $tileId)) === null) {
            return null;
        }

        $tile = new Tile($data->texture($tileInfo['image']));

        $clip = $this->position($tileInfo['columns'], $tileId - $tileInfo['firstgid']);
        $tile->clip = Util::createRect($tileInfo['tilewidth'], $tileInfo['tileheight']);
        $tile->clip->x = $tile->clip->w * $clip[0];
        $tile->clip->y = $tile->clip->h * $clip[1];

        $dest = $this->position($layer->width, $index);
        $tile->dest = Util::createRect($tileInfo['tilewidth'], $tileInfo['tileheight']);
        $tile->dest->x = $tile->dest->w * $dest[0];
        $tile->dest->y = $tile->dest->h * $dest[1];

        return $tile;
    }

    /**
     * @param array $info
     * @return Layer
     */
    private function loadLayer(array $info): Layer
    {
        $layer = new Layer($this->game, $info['width'], $info['height']);
        $layer->opacity = \min(255 * $info['opacity'], 255);
        $layer->visible = $info['visible'] ?? true;

        return $layer;
    }

    /**
     * @param int $columns
     * @param int $id
     * @return array
     */
    private function position(int $columns, int $id): array
    {
        return [$id % $columns, ($id / $columns) >> 0];
    }

    /**
     * @param Data $data
     * @param int $id
     * @return array|null
     */
    private function getTileInfo(Data $data, int $id): ?array
    {
        foreach ($data->get('tilesets') as $tiles) {
            [$from, $to] = [$tiles['firstgid'], $tiles['firstgid'] + $tiles['tilecount']];

            if ($id >= $from && $id < $to) {
                return $tiles;
            }
        }

        return null;
    }
}

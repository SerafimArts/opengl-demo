<?php

/**
 * This file is part of Battleground package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Serafim\Bic\Map;

use App\Game;
use Serafim\Bic\Progress\LoadingInterface;
use Serafim\Bic\Renderer\RendererInterface;
use Serafim\Bic\Renderer\TransformationInterface;
use Serafim\Bic\Renderer\View;

/**
 * Class Map
 */
class Map extends View
{
    /**
     * @var array|Layer[]
     */
    private array $layers = [];

    /**
     * @param Game $game
     * @param string $json
     * @return LoadingInterface
     */
    public static function fromJson(Game $game, string $json): LoadingInterface
    {
        return self::fromData($game, Data::fromJson($game, $json));
    }

    /**
     * @param Game $game
     * @param Data $data
     * @return LoadingInterface
     */
    public static function fromData(Game $game, Data $data): LoadingInterface
    {
        return (new Loader($game))->load($data);
    }

    /**
     * @param Game $game
     * @param string $map
     * @return LoadingInterface
     */
    public static function open(Game $game, string $map): LoadingInterface
    {
        return self::fromData($game, (new Package($map))->read($game));
    }

    /**
     * @param Layer $layer
     * @return void
     */
    public function addLayer(Layer $layer): void
    {
        $this->layers[] = $layer;
    }

    /**
     * @param RendererInterface $renderer
     * @param TransformationInterface $transform
     * @return void
     */
    public function render(RendererInterface $renderer, TransformationInterface $transform): void
    {
        foreach ($this->layers as $layer) {
            $layer->render($renderer, $transform);
        }
    }
}

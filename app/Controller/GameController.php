<?php

/**
 * This file is part of Battleground package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Controller;

use App\Game;
use App\View\Noise;
use App\View\Overlay;
use Serafim\Bic\Lifecycle\Annotation\OnRender;
use Serafim\Bic\Lifecycle\Annotation\OnUpdate;
use Serafim\Bic\Map\Map;
use Serafim\Bic\Renderer\Camera\CameraInterface;
use Serafim\Bic\Renderer\Camera\OrthographicCamera;

/**
 * Class MenuController
 */
class GameController
{
    /**
     * @var Game
     */
    private Game $game;

    /**
     * @var Noise
     */
    private Noise $noise;

    /**
     * @var Map
     */
    private Map $map;

    /**
     * @var Overlay
     */
    private Overlay $overlay;

    /**
     * @var float
     */
    private float $size = 0;

    /**
     * @var float
     */
    private float $position = 0;

    /**
     * @var CameraInterface
     */
    private CameraInterface $camera;

    /**
     * GameController constructor.
     *
     * @param Game $game
     * @param Map $map
     */
    public function __construct(Game $game, Map $map)
    {
        $this->game = $game;
        $this->map = $map;
        $this->noise = new Noise($game);
        $this->overlay = new Overlay($game);

        $this->camera = new OrthographicCamera($game->viewport);
    }

    /**
     * @OnUpdate()
     *
     * @param float $delta
     * @return void
     */
    public function onUpdate(float $delta): void
    {
        $this->size += $delta / 10;
        if ($this->size > 360) {
            $this->size -= 360;
        }

        $this->position += $delta / 3.3;
        if ($this->position > 360) {
            $this->position -= 360;
        }

        $this->camera->size->x = \max(1, \abs(\sin($this->size)) + 1);
        $this->camera->size->y = \max(1, \abs(\sin($this->size)) + 1);

        $this->camera->position->x = \abs(\sin($this->position) * 400);
        $this->camera->position->y = \abs(\cos($this->position) * 1000);

        $this->noise->update($delta);
    }

    /**
     * @OnRender()
     *
     * @param float $delta
     * @return void
     */
    public function onRender(float $delta): void
    {
        $this->map->render($this->game->renderer, $this->camera);

        $this->noise->render($this->game->renderer, $this->game->viewport);
        $this->overlay->render($this->game->renderer, $this->game->viewport);
    }
}

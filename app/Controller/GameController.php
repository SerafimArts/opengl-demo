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
use FFI\CData;
use Serafim\Bic\Lifecycle\Annotation\OnMouseWheel;
use Serafim\Bic\Lifecycle\Annotation\OnRender;
use Serafim\Bic\Lifecycle\Annotation\OnUpdate;
use Serafim\Bic\Map\Map;
use Serafim\Bic\Renderer\Camera\CameraInterface;
use Serafim\Bic\Renderer\Camera\OrthographicCamera;
use Serafim\SDL\EventPtr;

/**
 * Class MenuController
 */
class GameController
{
    /**
     * @var float
     */
    private const CAMERA_ZOOM_SPEED = .04;

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
        $this->noise->update($delta);
    }

    /**
     * Camera size on scroll.
     *
     * @OnMouseWheel()
     * @param CData|EventPtr $event
     */
    public function onMouseWheel(CData $event): void
    {
        if ($event->y === 1) {
            $this->camera->size->x += self::CAMERA_ZOOM_SPEED;
            $this->camera->size->y += self::CAMERA_ZOOM_SPEED;
        }

        if ($event->y === -1) {
            if (($this->camera->size->x - self::CAMERA_ZOOM_SPEED) <= 1) {
                $this->camera->size->x = 1;
            } else {
                $this->camera->size->x -= self::CAMERA_ZOOM_SPEED;
            }

            if (($this->camera->size->y - self::CAMERA_ZOOM_SPEED) <= 1) {
                $this->camera->size->y = 1;
            } else {
                $this->camera->size->y -= self::CAMERA_ZOOM_SPEED;
            }
        }
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

<?php

declare(strict_types=1);

namespace App\Controller;

use App\Game;
use App\View\FrameRate;
use App\View\Noise;
use App\View\Overlay;
use FFI\CData;
use Serafim\Bic\Lifecycle\Annotation\OnEvent;
use Serafim\Bic\Lifecycle\Annotation\OnMouseMove;
use Serafim\Bic\Lifecycle\Annotation\OnMouseWheel;
use Serafim\Bic\Lifecycle\Annotation\OnRender;
use Serafim\Bic\Lifecycle\Annotation\OnUpdate;
use Serafim\Bic\Map\Map;
use Serafim\Bic\Renderer\Camera\CameraInterface;
use Serafim\Bic\Renderer\Camera\OrthographicCamera;
use Serafim\SDL\EventPtr;
use Serafim\SDL\Kernel\Event\Type;
use Serafim\SDL\MouseMotionEvent;

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
     * @var array|null
     */
    private ?array $drag = null;

    /**
     * @var FrameRate
     */
    private FrameRate $rate;

    /**
     * @var float
     */
    private float $cameraSize = 1;

    /**
     * @param Game $game
     * @param Map $map
     */
    public function __construct(Game $game, Map $map)
    {
        $this->game = $game;
        $this->map = $map;
        $this->noise = new Noise($game);
        $this->overlay = new Overlay($game);
        $this->rate = new FrameRate($game);

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
        $this->rate->update($delta);
    }

    /**
     * @OnMouseMove()
     * @param CData|MouseMotionEvent $move
     * @return void
     */
    public function onMouseMove(CData $move): void
    {
        if ($this->drag === null) {
            return;
        }

        $this->camera->position->x -= $move->x - $this->drag[0];
        $this->camera->position->y -= $move->y - $this->drag[1];

        $this->drag = [$move->x, $move->y];
    }

    /**
     * @OnEvent(Type::SDL_MOUSEBUTTONDOWN)
     * @param CData|EventPtr $event
     * @return void
     */
    public function onDrag(CData $event): void
    {
        $this->drag = [$event->button->x, $event->button->y];
    }

    /**
     * @OnEvent(Type::SDL_MOUSEBUTTONUP)
     * @return void
     */
    public function onDrop(): void
    {
        $this->drag = null;
    }

    /**
     * Camera size on scroll.
     *
     * @OnMouseWheel()
     * @param CData|EventPtr $event
     */
    public function onMouseWheel(CData $event): void
    {
        switch (true) {
            case $event->y === 1:
                $this->cameraSize += self::CAMERA_ZOOM_SPEED;
                break;

            case $event->y === -1 && $this->cameraSize - self::CAMERA_ZOOM_SPEED <= 1:
                $this->cameraSize = 1;
                break;

            case $event->y === -1:
                $this->cameraSize -= self::CAMERA_ZOOM_SPEED;
                break;
        }

        $this->camera->size->x = $this->camera->size->y = $this->cameraSize;
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
        $this->rate->render($this->game->renderer, $this->game->viewport);
    }
}

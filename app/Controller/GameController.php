<?php

declare(strict_types=1);

namespace App\Controller;

use App\Game;
use App\View\FrameRate;
use App\View\Noise;
use App\View\Overlay;
use Bic\UI\Keyboard\Key;
use FFI\CData;
use Serafim\Bic\Lifecycle\Annotation\OnEvent;
use Serafim\Bic\Lifecycle\Annotation\OnKeyDown;
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
    private const CAMERA_ZOOM_SPEED = .06;
    private const CAMERA_ZOOM_MAX   = 2.;
    private const CAMERA_ZOOM_MIN   = .8;

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

    public function __construct(Game $game, Map $map)
    {
        $this->game = $game;
        $this->map = $map;
        $this->noise = new Noise($game);
        $this->overlay = new Overlay($game);
        $this->rate = new FrameRate($game);

        $this->camera = new OrthographicCamera($game->viewport);
    }

    #[OnUpdate]
    public function onUpdate(float $delta): void
    {
        $this->noise->update($delta);
        $this->rate->update($delta);
    }

    /**
     * @param CData|MouseMotionEvent $move
     */
    #[OnMouseMove]
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
     * @param CData|EventPtr $event
     */
    #[OnEvent(type: Type::SDL_MOUSEBUTTONDOWN)]
    public function onDrag(CData $event): void
    {
        $this->drag = [$event->button->x, $event->button->y];
    }

    /**
     * @return void
     */
    #[OnEvent(type: Type::SDL_MOUSEBUTTONUP)]
    public function onDrop(): void
    {
        $this->drag = null;
    }

    #[OnKeyDown(key: Key::ESCAPE)]
    public function onCloseApplication(): void
    {
        exit;
    }

    /**
     * Camera size on scroll.
     *
     * @param CData|EventPtr $event
     */
    #[OnMouseWheel]
    public function onMouseWheel(CData $event): void
    {
        switch (true) {
            case $event->y === 1:
                if ($this->cameraSize + self::CAMERA_ZOOM_SPEED >= self::CAMERA_ZOOM_MAX) {
                    $this->cameraSize = self::CAMERA_ZOOM_MAX;
                } else {
                    $this->cameraSize += self::CAMERA_ZOOM_SPEED;
                }
                break;

            case $event->y === -1:
                if ($this->cameraSize - self::CAMERA_ZOOM_SPEED <= self::CAMERA_ZOOM_MIN) {
                    $this->cameraSize = self::CAMERA_ZOOM_MIN;
                } else {
                    $this->cameraSize -= self::CAMERA_ZOOM_SPEED;
                }
                break;
        }

        $this->camera->size->x = $this->camera->size->y = $this->cameraSize;
    }

    /**
     * @param float $delta
     * @return void
     */
    #[OnRender]
    public function onRender(float $delta): void
    {
        $this->map->render($this->game->renderer, $this->camera);

        $this->noise->render($this->game->renderer, $this->game->viewport);
        $this->overlay->render($this->game->renderer, $this->game->viewport);
        $this->rate->render($this->game->renderer, $this->game->viewport);
    }
}

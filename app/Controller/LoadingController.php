<?php

declare(strict_types=1);

namespace App\Controller;

use App\Game;
use App\View\Loading;
use App\View\Noise;
use App\View\Preloader;
use Illuminate\Contracts\Container\BindingResolutionException;
use Serafim\Bic\Lifecycle\Annotation\OnRender;
use Serafim\Bic\Lifecycle\Annotation\OnUpdate;
use Serafim\Bic\Map\Map;
use Serafim\Bic\Math\Vector2;
use Serafim\Bic\Progress\LoadingInterface;
use Serafim\Bic\Renderer\ViewInterface;

class LoadingController
{
    /**
     * @var ViewInterface
     */
    public ViewInterface $loading;

    /**
     * @var ViewInterface
     */
    public ViewInterface $preloader;

    /**
     * @var Game
     */
    private Game $game;

    /**
     * @var Noise
     */
    private Noise $noise;

    /**
     * @var float
     */
    private float $timer = 0;

    /**
     * @var LoadingInterface
     */
    private LoadingInterface $progress;

    public function __construct(Game $game)
    {
        $this->game = $game;

        $this->preloader = new Preloader($game, new Vector2(1765, 930));
        $this->loading = new Loading($game);
        $this->noise = new Noise($game);

        $this->progress = Map::open($game, $game->app->resourcesPath('maps/example.map'));
    }

    /**
     * @param float $delta
     * @return void
     * @throws BindingResolutionException
     */
    #[OnUpdate]
    public function onUpdate(float $delta): void
    {
        $this->timer += $delta;

        $this->preloader->update($delta);
        $this->noise->update($delta);

        if (! $this->progress->isCompleted()) {
            $this->progress->next();

            return;
        }

        if ($this->timer > 2) {
            $this->game->show(GameController::class, [
                Map::class => $this->progress->result()
            ]);
        }
    }

    /**
     * @return void
     */
    #[OnRender]
    public function onRender(): void
    {
        $this->loading->render($this->game->renderer, $this->game->viewport);
        $this->noise->render($this->game->renderer, $this->game->viewport);
        $this->preloader->render($this->game->renderer, $this->game->viewport);
    }
}

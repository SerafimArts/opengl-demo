<?php

/**
 * This file is part of UnknownPlatformer package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Serafim\Bic\EventLoop;

use FFI\CData;
use Serafim\SDL\Event;
use Serafim\SDL\EventPtr;
use Serafim\SDL\SDL;
use Serafim\SDL\SDLNativeApiAutocomplete;

/**
 * Class EventLoop
 */
abstract class EventLoop implements LoopInterface
{
    /**
     * @var SDL|SDLNativeApiAutocomplete
     */
    protected SDL $sdl;

    /**
     * @var bool
     */
    protected bool $paused = false;

    /**
     * @var bool
     */
    protected bool $running = false;

    /**
     * @var WorkerInterface|null
     */
    private ?WorkerInterface $worker = null;

    /**
     * EventLoop constructor.
     */
    public function __construct()
    {
        $this->sdl = SDL::getInstance();
    }

    /**
     * @param WorkerInterface|null $worker
     * @return void
     */
    public function use(?WorkerInterface $worker): void
    {
        $this->worker = $worker;
    }

    /**
     * @param float $delta
     * @return void
     */
    protected function render(float $delta): void
    {
        if ($this->worker !== null) {
            $this->worker->onRender($delta);
        }
    }

    /**
     * @param CData|Event $event
     * @return void
     */
    protected function poll(CData $event): void
    {
        if ($event->type === SDL::SDL_QUIT) {
            $this->running = false;
        }

        if ($this->worker !== null) {
            $this->worker->onEvent($event);
        }
    }

    /**
     * @param float $delta
     * @return void
     */
    protected function update(float $delta): void
    {
        if ($this->worker !== null && $this->paused === false) {
            $this->worker->onUpdate($delta);
        }
    }

    /**
     * @return void
     */
    public function pause(): void
    {
        if ($this->paused === false && $this->worker !== null) {
            $this->worker->onPause();
        }

        $this->paused = true;
    }

    /**
     * @return void
     */
    public function resume(): void
    {
        if ($this->paused === true && $this->worker !== null) {
            $this->worker->onResume();
        }

        $this->paused = false;
    }

    /**
     * {@inheritDoc}
     */
    public function run(int $frameRate = self::DEFAULT_FRAME_RATE, int $updateRate = self::DEFAULT_UPDATE_RATE): void
    {
        $this->paused = false;

        if ($this->running) {
            return;
        }

        /** @var Event $event */
        $event = $this->sdl->new('SDL_Event', false);
        $eventPtr = $this->sdl::addr($event);

        try {
            $this->running = true;

            $this->execute($frameRate, $updateRate, $event, $eventPtr);
        } finally {
            $this->sdl::free($eventPtr);
        }
    }

    /**
     * @param int $frameRate
     * @param int $updateRate
     * @param CData|Event $event
     * @param CData|EventPtr $ptr
     * @return void
     */
    abstract protected function execute(int $frameRate, int $updateRate, CData $event, CData $ptr): void;

    /**
     * @return void
     */
    public function stop(): void
    {
        $this->running = false;

        $this->sdl->SDL_Quit();
    }
}

<?php

declare(strict_types=1);

namespace Serafim\Bic\EventLoop;

use FFI\CData;

class OrderedEventLoop extends EventLoop
{
    /**
     * @var Timer
     */
    public Timer $render;

    /**
     * @var Timer
     */
    public Timer $updates;

    public function __construct()
    {
        parent::__construct();

        $this->render = new Timer(self::DEFAULT_FRAME_RATE);
        $this->updates = new Timer(self::DEFAULT_UPDATE_RATE);
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(int $frameRate, int $updateRate, CData $event, CData $eventPtr): void
    {
        $this->render->rate($frameRate)->touch();
        $this->updates->rate($updateRate)->touch();

        while ($this->running) {
            $now = \microtime(true);

            if (($delta = $this->updates->next($now)) !== null) {
                $this->update($delta);
            }

            if (($delta = $this->render->next($now)) !== null) {
                $this->render($delta);
            }

            while ($this->sdl->SDL_PollEvent($eventPtr)) {
                $this->poll($event);
            }
        }
    }
}

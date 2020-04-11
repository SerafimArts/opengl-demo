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

/**
 * Class BlockingEventLoop
 */
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

    /**
     * BlockingEventLoop constructor.
     */
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

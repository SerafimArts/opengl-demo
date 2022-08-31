<?php

declare(strict_types=1);

namespace App\Dispatcher\Subscriber;

use Bic\Dispatcher\Attribute\OnEvent;
use Bic\UI\Window\Event\WindowCloseEvent;
use Bic\UI\Window\FactoryInterface;

final class WindowEventsSubscriber
{
    /**
     * @param FactoryInterface $windows
     */
    public function __construct(
        private readonly FactoryInterface $windows,
    ) {
    }

    /**
     * In the case that the event of pressing the window close button occurs,
     * this window should be closed.
     *
     * @param WindowCloseEvent $event
     * @return void
     */
    #[OnEvent(WindowCloseEvent::class)]
    public function onWindowClose(WindowCloseEvent $event): void
    {
        $event->target->close();

        // In some cases, the window may remain in
        // RAM, so we should forcibly remove it from
        // the list of processed windows.
        if ($this->windows->count() > 0) {
            $this->windows->detach($event->target);
        }
    }
}

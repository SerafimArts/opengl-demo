<?php

declare(strict_types=1);

namespace App\Dispatcher\Subscriber;

use App\Controller\HomeController;
use Bic\Controller\ManagerInterface;
use Bic\Dispatcher\Attribute\OnEvent;
use Bic\Foundation\Event\AppStarted;
use Bic\UI\Window\Event\WindowCloseEvent;
use Bic\UI\Window\FactoryInterface;

final class AppLifecycleSubscriber
{
    /**
     * @param FactoryInterface $windows
     * @param ManagerInterface $controllers
     */
    public function __construct(
        private readonly FactoryInterface $windows,
        private readonly ManagerInterface $controllers,
    ) {
    }

    /**
     * In the case that the event of pressing the window close button occurs,
     * this window should be closed.
     *
     * @param WindowCloseEvent $ev
     * @return void
     */
    #[OnEvent(WindowCloseEvent::class)]
    public function onWindowClose(WindowCloseEvent $ev): void
    {
        $ev->target->close();

        // In some cases, the window may remain in
        // RAM, so we should forcibly remove it from
        // the list of processed windows.
        if ($this->windows->count() > 0) {
            $this->windows->detach($ev->target);
        }
    }

    /**
     * Choosing the main controller when starting the application.
     *
     * @param AppStarted $ev
     * @return void
     */
    #[OnEvent(AppStarted::class)]
    public function onAppStarted(AppStarted $ev): void
    {
        $this->controllers->use(HomeController::class);
    }
}

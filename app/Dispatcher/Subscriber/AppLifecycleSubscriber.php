<?php

declare(strict_types=1);

namespace App\Dispatcher\Subscriber;

use App\Controller\HomeController;
use Bic\Controller\ManagerInterface;
use Bic\Dispatcher\Attribute\OnEvent;
use Bic\Dispatcher\DispatcherInterface;
use Bic\Foundation\Event\AppLaunchEvent;
use Bic\Foundation\Event\AppUpdateEvent;
use Bic\Renderer\Event\AfterRenderEvent;
use Bic\Renderer\Event\BeforeRenderEvent;
use Bic\Renderer\Event\RenderEvent;
use Bic\Renderer\RendererInterface;

final class AppLifecycleSubscriber
{
    /**
     * @param ManagerInterface $controllers
     * @param RendererInterface $renderer
     * @param DispatcherInterface $dispatcher
     */
    public function __construct(
        private readonly ManagerInterface $controllers,
        private readonly RendererInterface $renderer,
        private readonly DispatcherInterface $dispatcher,
    ) {
    }

    /**
     * Choosing the main controller when starting the application.
     *
     * @return void
     */
    #[OnEvent(AppLaunchEvent::class)]
    public function onAppLaunch(): void
    {
        $this->controllers->use(HomeController::class);
    }

    /**
     * Called every "tick" of the application.
     *
     * @param AppUpdateEvent $event
     * @return void
     */
    #[OnEvent(AppUpdateEvent::class)]
    public function onAppUpdate(AppUpdateEvent $event): void
    {
        $this->dispatcher->dispatch(new BeforeRenderEvent($this->renderer, $event->delta));
        $this->renderer->clean();
        $this->dispatcher->dispatch(new RenderEvent($this->renderer, $event->delta));
        $this->renderer->draw();
        $this->dispatcher->dispatch(new AfterRenderEvent($this->renderer, $event->delta));
    }
}

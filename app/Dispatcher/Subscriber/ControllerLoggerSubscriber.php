<?php

declare(strict_types=1);

namespace App\Dispatcher\Subscriber;

use Bic\Controller\Event\ControllerHideEvent;
use Bic\Controller\Event\ControllerShowEvent;
use Bic\Controller\Event\ControllerSwitchEvent;
use Bic\Dispatcher\Attribute\OnEvent;
use Psr\Log\LoggerInterface;

/**
 * Responsible for informing controller events.
 */
final class ControllerLoggerSubscriber
{
    /**
     * @param LoggerInterface $logger
     */
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {
    }

    /**
     * The event listener that is fired (invoked) when the
     * controller is shown.
     *
     * @param ControllerShowEvent $e
     * @return void
     */
    #[OnEvent(ControllerShowEvent::class)]
    public function onShow(ControllerShowEvent $e): void
    {
        $this->logger->debug('[Controller] Show ' . ($e->controller)::class, [
            'controller' => $e->controller
        ]);
    }

    /**
     * The event listener that is called (invoked) when the controller
     * switches from one to another.
     *
     * @param ControllerSwitchEvent $e
     * @return void
     */
    #[OnEvent(ControllerSwitchEvent::class)]
    public function onSwitch(ControllerSwitchEvent $e): void
    {
        $from = ($e->previous)::class;
        $to = ($e->controller)::class;

        $this->logger->debug('[Controller] Switch ' . $from . ' -> ' . $to, [
            'from' => $e->previous,
            'to' => $e->controller,
        ]);
    }

    /**
     * The event listener that is fired (invoked) when the
     * controller is hidden.
     *
     * @param ControllerHideEvent $e
     * @return void
     */
    #[OnEvent(ControllerHideEvent::class)]
    public function onHide(ControllerHideEvent $e): void
    {
        $this->logger->debug('[Controller] Hide ' . ($e->controller)::class, [
            'controller' => $e->controller
        ]);
    }
}

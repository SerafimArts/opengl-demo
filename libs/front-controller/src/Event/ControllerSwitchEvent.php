<?php

declare(strict_types=1);

namespace Bic\Controller\Event;

final class ControllerSwitchEvent extends ControllerEvent
{
    public function __construct(
        public readonly object $previous,
        object $controller,
    ) {
        parent::__construct($controller);
    }

    /**
     * @return object
     */
    public function getPreviousController(): object
    {
        return $this->previous;
    }
}

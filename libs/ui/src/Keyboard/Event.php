<?php

declare(strict_types=1);

namespace Bic\UI\Keyboard;

use Bic\UI\Event as UIEvent;
use Bic\UI\Window\WindowInterface;

/**
 * @template TTargetWindow of WindowInterface
 *
 * @template-extends UIEvent<TTargetWindow>
 */
abstract class Event extends UIEvent
{
    /**
     * @psalm-param TTargetWindow $target
     * @param KeyInterface $key
     * @psalm-param int-mask-of<Modifier::*>|Modifier::* $modifiers
     */
    public function __construct(
        WindowInterface $target,
        public readonly KeyInterface $key,
        public readonly int $modifiers = Modifier::NONE,
    ) {
        parent::__construct($target);
    }
}

<?php

declare(strict_types=1);

namespace Bic\UI\Keyboard\Event;

use Bic\UI\Event;
use Bic\UI\Keyboard\KeyInterface;
use Bic\UI\Keyboard\Modifier;
use Bic\UI\Window\WindowInterface;
use JetBrains\PhpStorm\ExpectedValues;

/**
 * @psalm-import-type KeyModifierFlag from Modifier
 * @psalm-import-type KeyModifierFlags from Modifier
 *
 * @template TTargetWindow of WindowInterface
 *
 * @template-extends Event<TTargetWindow>
 */
abstract class KeyEvent extends Event
{
    /**
     * @param TTargetWindow $target
     * @param KeyInterface $key
     * @param KeyModifierFlags|KeyModifierFlag $modifiers
     */
    public function __construct(
        WindowInterface $target,
        public readonly KeyInterface $key,
        #[ExpectedValues(flagsFromClass: Modifier::class)]
        public readonly int $modifiers = Modifier::NONE,
    ) {
        parent::__construct($target);
    }
}

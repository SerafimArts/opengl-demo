<?php

declare(strict_types=1);

namespace Bic\UI;

use Bic\UI\Window\WindowInterface;

/**
 * @template TWindow of WindowInterface
 *
 * @template-extends \Traversable<array-key, TWindow>
 */
interface ManagerInterface extends RunnableInterface, \Traversable, \Countable
{
    /**
     * @param TWindow $window
     *
     * @return void
     */
    public function detach(WindowInterface $window): void;
}

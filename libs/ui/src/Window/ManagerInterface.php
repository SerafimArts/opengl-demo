<?php

declare(strict_types=1);

namespace Bic\UI\Window;

use Ramsey\Uuid\UuidInterface;

/**
 * @template TWindow of WindowInterface
 *
 * @template-extends \Traversable<UuidInterface, TWindow>
 */
interface ManagerInterface extends \Traversable, \Countable
{
    /**
     * @param UuidInterface $id
     * @return WindowInterface|null
     */
    public function find(UuidInterface $id): ?WindowInterface;

    /**
     * @param WindowInterface $window
     * @return void
     */
    public function detach(WindowInterface $window): void;
}



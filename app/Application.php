<?php

/**
 * This file is part of Bic Engine package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App;

use Bic\Foundation\Kernel;
use Bic\UI\Window\Event\WindowCloseEvent;
use Bic\UI\Window\FactoryInterface;

final class Application extends Kernel
{
    /**
     * @var FactoryInterface
     */
    private readonly FactoryInterface $windows;

    /**
     * @psalm-taint-sink file $root
     * @param non-empty-string $root
     * @throws \Exception
     */
    public function __construct(string $root)
    {
        parent::__construct($root, (bool)($_SERVER['APP_DEBUG'] ?? 0));

        try {
            $this->windows = $this->get(FactoryInterface::class);
        } catch (\Throwable $e) {
            exit($this->throw($e));
        }
    }

    /**
     * @return void
     * @throws \Exception
     */
    protected function start(): void
    {
        $renderer = $this->get(Renderer::class);

        foreach ($this->windows->poll(blocking: false) as $event) {
            if ($event !== null) {
                $this->dispatch($event);
            }

            $renderer->clean();
            $renderer->draw();
        }
    }
}

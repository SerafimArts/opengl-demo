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
use Bic\UI\Window\FactoryInterface;

final class Application extends Kernel
{
    /**
     * @return void
     * @throws \Exception
     */
    protected function start(): void
    {
        $renderer = $this->get(Renderer::class);

        $windows = $this->get(FactoryInterface::class);
        foreach ($windows->poll(blocking: false) as $event) {
            if ($event !== null) {
                $this->dispatch($event);
            }

            $renderer->clean();
            $renderer->draw();
        }
    }
}

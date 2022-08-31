<?php

declare(strict_types=1);

namespace App\Controller;

use Bic\Dispatcher\Attribute\OnEvent;
use Bic\Renderer\Event\RenderEvent;
use Bic\UI\Mouse\Event\MouseMoveEvent;

class HomeController
{
    #[OnEvent(MouseMoveEvent::class)]
    public function down(MouseMoveEvent $e): void
    {
        //echo $e->x . ' x ' . $e->y . "\n";
    }

    #[OnEvent(RenderEvent::class)]
    public function update(RenderEvent $e): void
    {

    }
}

<?php

declare(strict_types=1);

use App\Game;

if (!is_file(__DIR__ . '/vendor/autoload.php')) {
    throw new \LogicException();
}

require __DIR__ . '/vendor/autoload.php';

$game = new Game();
$game->run();





<?php

declare(strict_types=1);

use App\Game;

if (!is_file(__DIR__ . '/vendor/autoload.php')) {
    fwrite(STDERR, 'Install dependencies using Composer');
    exit(1);
}

require __DIR__ . '/vendor/autoload.php';

$game = new Game();
$game->run();





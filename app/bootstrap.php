<?php

declare(strict_types=1);

// -----------------------------------------------------------------------------
//  Initial Application Configuration
// -----------------------------------------------------------------------------

const PATHNAME_COMPOSER = __DIR__ . '/../vendor/autoload.php';
const PATHNAME_DOTENV = __DIR__ . '/../.env';


// -----------------------------------------------------------------------------
//  Load Composer Autoloader
// -----------------------------------------------------------------------------

if (!is_file(PATHNAME_COMPOSER)) {
    fwrite(STDERR, <<<'MESSAGE'
    You need to set up the project dependencies using Composer
    MESSAGE);
    die(1);
}

$autoload = require PATHNAME_COMPOSER;


// -----------------------------------------------------------------------------
//  Load Environment Variables
// -----------------------------------------------------------------------------

if (is_file(PATHNAME_DOTENV)) {
    $dotenv = \Dotenv\Dotenv::createUnsafeImmutable(
        \dirname(PATHNAME_DOTENV),
        \basename(PATHNAME_DOTENV),
    );
    $dotenv->load();
}

return $autoload;

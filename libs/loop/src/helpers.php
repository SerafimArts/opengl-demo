<?php

declare(strict_types=1);

namespace Bic\Loop;

function all(iterable $tasks): \Generator
{
    return Task::all($tasks);
}

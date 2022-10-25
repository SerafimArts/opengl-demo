<?php

declare(strict_types=1);

namespace Bic\Async;

function all(\Fiber $task, \Fiber ...$tasks): array
{
    return Task::all($task, ...$tasks);
}

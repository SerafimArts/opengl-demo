<?php

declare(strict_types=1);

namespace Bic\UI;

interface RunnableInterface
{
    /**
     * @return void
     */
    public function run(): void;
}

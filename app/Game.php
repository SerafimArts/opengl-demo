<?php

declare(strict_types=1);

namespace App;

use App\Controller\LoadingController;
use Illuminate\Contracts\Container\BindingResolutionException;
use Serafim\Bic\Application;
use Serafim\Bic\Lifecycle\Lifecycle;

final class Game extends Lifecycle
{
    /**
     * @throws BindingResolutionException
     */
    public function __construct()
    {
        parent::__construct(new Application(__DIR__ . '/..'));

        $this->show(LoadingController::class);
    }
}

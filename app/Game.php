<?php

/**
 * This file is part of Bic Engine package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App;

use App\Controller\LoadingController;
use Illuminate\Contracts\Container\BindingResolutionException;
use Serafim\Bic\Application;
use Serafim\Bic\Lifecycle\Lifecycle;

/**
 * Class Game
 */
final class Game extends Lifecycle
{
    /**
     * Game constructor.
     *
     * @throws BindingResolutionException
     */
    public function __construct()
    {
        parent::__construct(new Application(__DIR__ . '/..'));

        $this->show(LoadingController::class);
    }
}

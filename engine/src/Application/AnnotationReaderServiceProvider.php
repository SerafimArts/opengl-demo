<?php

/**
 * This file is part of Battleground package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Serafim\Bic\Application;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\Reader;

/**
 * Class AnnotationReaderServiceProvider
 */
class AnnotationReaderServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        AnnotationRegistry::registerLoader(static function (string $class) {
            return \class_exists($class);
        });

        $this->app->singleton(Reader::class, static function () {
            return new AnnotationReader();
        });
    }
}

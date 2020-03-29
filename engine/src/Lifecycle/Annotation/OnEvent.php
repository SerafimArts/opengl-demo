<?php

/**
 * This file is part of Bic Engine package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Serafim\Bic\Lifecycle\Annotation;

/**
 * @Annotation
 */
class OnEvent extends Lifecycle
{
    /**
     * @var string
     */
    protected const DEFAULT_VALUE = 'type';

    /**
     * @var int
     */
    public ?int $type = null;
}

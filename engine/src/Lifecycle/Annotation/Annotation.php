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
 * Class Annotation
 */
abstract class Annotation
{
    /**
     * @var string
     */
    protected const DEFAULT_VALUE = 'value';

    /**
     * Annotation constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        foreach ($attributes as $key => $value) {
            if ($key === 'value') {
                $key = static::DEFAULT_VALUE;
            }

            $this->$key = $value;
        }
    }
}

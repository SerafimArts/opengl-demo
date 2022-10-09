<?php

declare(strict_types=1);

namespace Serafim\Bic\Lifecycle\Annotation;

use Doctrine\Common\Annotations\Annotation\Target;

/**
 * @Target({"METHOD"})
 */
abstract class Lifecycle extends Annotation
{
}

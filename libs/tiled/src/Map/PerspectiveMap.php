<?php

declare(strict_types=1);

namespace Bic\Tiled\Map;

abstract class PerspectiveMap extends Map
{
    /**
     * @var StaggerAxis
     */
    public StaggerAxis $staggerAxis = StaggerAxis::X;

    /**
     * @var StaggerIndex
     */
    public StaggerIndex $staggerIndex = StaggerIndex::EVEN;
}

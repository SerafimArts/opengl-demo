<?php

declare(strict_types=1);

namespace Bic\Loop\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function randomArrayValues(int $values = 10, int $bytes = 32): array
    {
        $result = [];

        for ($i = $values; $i > 0; --$i) {
            $result[] = \random_bytes($bytes);
        }

        return $result;
    }
}

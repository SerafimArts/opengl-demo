<?php

declare(strict_types=1);

namespace Bic\Audio\Device;

/**
 * @psalm-type DeviceID = positive-int
 */
interface DeviceInterface
{
    /**
     * @return DeviceID
     */
    public function getId(): int;

    /**
     * @return non-empty-string
     */
    public function getName(): string;
}

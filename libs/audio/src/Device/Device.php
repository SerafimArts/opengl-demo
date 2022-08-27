<?php

declare(strict_types=1);

namespace Bic\Audio\Device;

/**
 * @psalm-import-type DeviceID from DeviceInterface
 */
abstract class Device implements DeviceInterface
{
    /**
     * @param DeviceID $id
     * @param non-empty-string $name
     */
    public function __construct(
        private readonly int $id,
        private readonly string $name,
    ) {
    }

    /**
     * @return DeviceID
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return non-empty-string
     */
    public function getName(): string
    {
        return $this->name;
    }
}

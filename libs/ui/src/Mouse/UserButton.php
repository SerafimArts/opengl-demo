<?php

declare(strict_types=1);

namespace Bic\UI\Mouse;

/**
 * @psalm-import-type ButtonID from ButtonInterface
 * @package ui
 */
final class UserButton implements ButtonInterface
{
    /**
     * @var array<int, self>
     * @psalm-var array<ButtonID, self>
     */
    private static array $instances = [];

    /**
     * @psalm-param ButtonID $id
     */
    private function __construct(
        private readonly int $id,
    ) {
    }

    /**
     * @psalm-return ButtonID
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @psalm-param ButtonID $id
     * @return ButtonInterface
     */
    public static function create(int $id): ButtonInterface
    {
        return self::$instances[$id] ??= new self($id);
    }
}

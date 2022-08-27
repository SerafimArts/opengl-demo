<?php

declare(strict_types=1);

namespace Bic\UI\Mouse;

/**
 * @psalm-import-type ButtonID from ButtonInterface
 */
final class UserButton implements ButtonInterface
{
    /**
     * @var array<ButtonID, self>
     */
    private static array $instances = [];

    /**
     * @param ButtonID $id
     */
    private function __construct(
        private readonly int $id,
    ) {
    }

    /**
     * @return ButtonID
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param ButtonID $id
     * @return ButtonInterface
     */
    public static function create(int $id): ButtonInterface
    {
        return self::$instances[$id] ??= new self($id);
    }
}

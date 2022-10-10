<?php

declare(strict_types=1);

namespace Bic\UI\Keyboard;

/**
 * @psalm-import-type KeyID from KeyInterface
 */
final class UserKey implements KeyInterface
{
    /**
     * @psalm-var array<KeyID, self>
     * @var array<int, self>
     */
    private static array $instances = [];

    /**
     * @param KeyID $id
     * @param int $id
     */
    private function __construct(
        private readonly int $id,
    ) {
    }

    /**
     * @psalm-return KeyID
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param KeyID $id
     * @param int $id
     * @return KeyInterface
     */
    public static function create(int $id): KeyInterface
    {
        return self::$instances[$id] ??= new self($id);
    }
}

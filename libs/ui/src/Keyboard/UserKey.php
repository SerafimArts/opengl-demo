<?php

declare(strict_types=1);

namespace Bic\UI\Keyboard;

/**
 * @psalm-import-type KeyID from KeyInterface
 */
final class UserKey implements KeyInterface
{
    /**
     * @var array<KeyID, self>
     */
    private static array $instances = [];

    /**
     * @param KeyID $id
     */
    private function __construct(
        private readonly int $id,
    ) {
    }

    /**
     * @return KeyID
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param KeyID $id
     * @return KeyInterface
     */
    public static function create(int $id): KeyInterface
    {
        return self::$instances[$id] ??= new self($id);
    }
}

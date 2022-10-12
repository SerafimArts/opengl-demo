<?php

declare(strict_types=1);

namespace Bic\Image\Stream;

use JetBrains\PhpStorm\ExpectedValues;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Bic\Image
 */
final class TypedStream implements StreamInterface
{
    /**
     * @var Endianness
     */
    private Endianness $endianness;

    /**
     * @param StreamInterface $stream
     * @param Endianness|null $endianness
     */
    public function __construct(
        private readonly StreamInterface $stream,
        Endianness $endianness = null,
    ) {
        $this->endianness = $endianness ?? Endianness::auto();
    }

    /**
     * @return $this
     */
    public function withLittleEndian(): self
    {
        $self = clone $this;
        $self->endianness = Endianness::BIG;

        return $self;
    }

    /**
     * @return $this
     */
    public function withBigEndian(): self
    {
        $self = clone $this;
        $self->endianness = Endianness::BIG;

        return $self;
    }

    /**
     * {@inheritDoc}
     */
    public function read(int $bytes): string
    {
        return $this->stream->read($bytes);
    }

    /**
     * {@inheritDoc}
     */
    public function seek(int $offset): int
    {
        return $this->stream->seek($offset);
    }

    /**
     * {@inheritDoc}
     */
    public function move(int $offset): int
    {
        return $this->stream->move($offset);
    }

    /**
     * {@inheritDoc}
     */
    public function offset(): int
    {
        return $this->stream->offset();
    }

    /**
     * @param string|Type $type
     *
     * @return mixed
     */
    private function readAs(string|Type $type): mixed
    {
        $type = Type::of($type);

        $result = \unpack($type->value, $this->read($type->getSize()));

        return \reset($result);
    }

    /**
     * @return int
     */
    public function int8(): int
    {
        return (int)$this->readAs(Type::INT8);
    }

    /**
     * @alias of {@see self::int8()}
     *
     * @return int
     */
    public function byte(): int
    {
        return $this->int8();
    }

    /**
     * @return positive-int
     */
    public function uint8(): int
    {
        return (int)$this->readAs(Type::UINT8);
    }

    /**
     * @alias of {@see self::uint8()}
     *
     * @return positive-int
     */
    public function ubyte(): int
    {
        return $this->uint8();
    }

    /**
     * @return int
     */
    public function int16(): int
    {
        return (int)$this->readAs(Type::INT16);
    }

    /**
     * @alias of {@see self::int16()}
     *
     * @return int
     */
    public function short(): int
    {
        return $this->int16();
    }

    /**
     * @return positive-int
     */
    public function uint16(Endianness $endianness = null): int
    {
        $endianness ??= $this->endianness;

        return (int)$this->readAs($endianness === Endianness::LITTLE ? Type::UINT16_LE : Type::UINT16_BE);
    }

    /**
     * @alias of {@see self::uint16()}
     *
     * @return positive-int
     */
    public function word(Endianness $endianness = null): int
    {
        return $this->uint16($endianness);
    }

    /**
     * @alias of {@see self::uint16()}
     *
     * @return positive-int
     */
    public function ushort(Endianness $endianness = null): int
    {
        return $this->uint16($endianness);
    }

    /**
     * @return int
     */
    public function int32(): int
    {
        return (int)$this->readAs(Type::INT32);
    }

    /**
     * @alias of {@see self::int32()}
     *
     * @return int
     */
    public function int(): int
    {
        return $this->int32();
    }

    /**
     * @alias of {@see self::int32()}
     *
     * @return int
     */
    public function long(): int
    {
        return $this->int32();
    }

    /**
     * @return positive-int
     */
    public function uint32(Endianness $endianness = null): int
    {
        $endianness ??= $this->endianness;

        return (int)$this->readAs($endianness === Endianness::LITTLE ? Type::UINT32_LE : Type::UINT32_BE);
    }

    /**
     * @alias of {@see self::uint32()}
     *
     * @return positive-int
     */
    public function dword(Endianness $endianness = null): int
    {
        return $this->uint32($endianness);
    }

    /**
     * @alias of {@see self::uint32()}
     *
     * @return positive-int
     */
    public function ulong(Endianness $endianness = null): int
    {
        return $this->uint32($endianness);
    }

    /**
     * @alias of {@see self::uint32()}
     *
     * @return positive-int
     */
    public function uint(Endianness $endianness = null): int
    {
        return $this->uint32($endianness);
    }

    /**
     * @return int
     */
    public function int64(): int
    {
        return (int)$this->readAs(Type::INT64);
    }

    /**
     * @alias of {@see self::int64()}
     *
     * @return int
     */
    public function quad(): int
    {
        return $this->int64();
    }

    /**
     * @return positive-int
     */
    public function uint64(Endianness $endianness = null): int
    {
        $endianness ??= $this->endianness;

        return (int)$this->readAs($endianness === Endianness::LITTLE ? Type::UINT64_LE : Type::UINT64_BE);
    }

    /**
     * @alias of {@see self::uint64()}
     *
     * @return positive-int
     */
    public function uquad(Endianness $endianness = null): int
    {
        return $this->uint64($endianness);
    }

    /**
     * @alias of {@see self::uint64()}
     *
     * @return positive-int
     */
    public function qword(Endianness $endianness = null): int
    {
        return $this->uint64($endianness);
    }

    /**
     * @return float
     */
    public function float32(Endianness $endianness = null): float
    {
        $endianness ??= $this->endianness;

        return (float)$this->readAs($endianness === Endianness::LITTLE ? Type::FLOAT32_LE : Type::FLOAT32_BE);
    }

    /**
     * @return float
     */
    public function float64(Endianness $endianness = null): float
    {
        $endianness ??= $this->endianness;

        return (float)$this->readAs($endianness === Endianness::LITTLE ? Type::FLOAT64_LE : Type::FLOAT64_BE);
    }

    /**
     * @return \DateTimeInterface
     */
    public function timestamp(Endianness $endianness = null): \DateTimeInterface
    {
        $timestamp = $this->uint32($endianness);

        return (new \DateTimeImmutable())->setTimestamp($timestamp);
    }

    /**
     * @param positive-int $size
     * @param string|Type $type
     *
     * @return array
     */
    public function array(
        int $size,
        #[ExpectedValues(valuesFromClass: Type::class)]
        string|Type $type
    ): array {
        $result = [];

        for ($i = 0; $i < $size; ++$i) {
            $result[] = $this->readAs($type);
        }

        return $result;
    }

    /**
     * @return string
     */
    public function char(): string
    {
        return $this->read(1);
    }

    /**
     * @param positive-int|null $size
     *
     * @return string
     */
    public function string(?int $size = null): string
    {
        if ($size === null) {
            $buffer = '';

            while (($char = $this->read(1)) !== "\x00") {
                $buffer .= $char;
            }

            return $buffer;
        }

        return \rtrim($this->read($size), "\x00");
    }

    /**
     * @template T of mixed
     *
     * @param callable(TypedStream): T $handler
     *
     * @return T
     */
    public function lookahead(callable $handler)
    {
        $offset = $this->offset();
        $result = $handler($this);
        $this->seek($offset);

        return $result;
    }

    /**
     * @param positive-int $bytes
     *
     * @return TypedStream
     */
    public function slice(int $bytes): TypedStream
    {
        $stream = \fopen('php://memory', 'ab+');
        \fwrite($stream, $this->read($bytes));
        \rewind($stream);

        return new self(new ResourceStream($stream, true), $this->endianness);
    }

    /**
     * @param positive-int $bytes
     *
     * @return array<bool>
     */
    public function bitmask(int $bytes): array
    {
        $result = [];

        for ($i = 0; $i < $bytes; ++$i) {
            $byte = \ord($this->read(1));
            $bits = \str_pad(\decbin($byte), 8, '0', \STR_PAD_LEFT);
            foreach (\str_split($bits) as $bit) {
                $result[] = (bool)(int)$bit;
            }
        }

        return $result;
    }
}

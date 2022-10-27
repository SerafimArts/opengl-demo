<?php

declare(strict_types=1);

namespace Bic\Binary;

use Bic\Binary\Exception\NonReadableException;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Bic\Binary
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
    public function rewind(): void
    {
        $this->stream->rewind();
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
    public function isCompleted(): bool
    {
        return $this->stream->isCompleted();
    }

    /**
     * {@inheritDoc}
     */
    public function offset(): int
    {
        return $this->stream->offset();
    }

    /**
     * @param Type $type
     * @param Endianness|null $endianness
     *
     * @return int|float
     */
    private function readAs(Type $type, Endianness $endianness = null): int|float
    {
        [1 => $data] = \unpack(
            $type->toPackFormat($endianness ?? $this->endianness),
            $this->read($type->getSize()),
        );

        return $data;
    }

    /**
     * @return int
     */
    public function int8(): int
    {
        $value = \ord($this->read(1));

        return $value & 0x80 ? $value - 0x100 : $value;
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
        return \ord($this->read(1));
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
        $buffer = $this->read(2);

        $value = \ord($buffer[0]) | \ord($buffer[1]) << 8;

        return \ord($buffer[1]) & 0x80 ? $value - 0x1_0000 : $value;
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
        $buffer = $this->read(2);

        if (($endianness ?? $this->endianness) === Endianness::LITTLE) {
            return \ord($buffer[0]) | (\ord($buffer[1]) << 8);
        }

        return \ord($buffer[1]) | (\ord($buffer[0]) << 8);
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
        [1 => $int32] = \unpack('l', $this->read(4));

        return $int32;
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
        [1 => $uint32] = \unpack(match ($endianness ?? $this->endianness) {
            Endianness::LITTLE => 'V',
            Endianness::BIG => 'N',
            default => 'L',
        }, $this->read(4));

        return $uint32;
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
        [1 => $int64] = \unpack('q', $this->read(8));

        return $int64;
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
        [1 => $uint64] = \unpack(match ($endianness ?? $this->endianness) {
            Endianness::LITTLE => 'P',
            Endianness::BIG => 'J',
            default => 'Q',
        }, $this->read(8));

        return $uint64;
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
     * @param Endianness|null $endianness
     *
     * @return float
     */
    public function float32(Endianness $endianness = null): float
    {
        [1 => $float] = \unpack(match ($endianness ?? $this->endianness) {
            Endianness::LITTLE => 'g',
            Endianness::BIG => 'G',
            default => 'f',
        }, $this->read(4));

        return $float;
    }

    /**
     * @param Endianness|null $endianness
     *
     * @return float
     */
    public function float64(Endianness $endianness = null): float
    {
        [1 => $double] = \unpack(match ($endianness ?? $this->endianness) {
            Endianness::LITTLE => 'e',
            Endianness::BIG => 'E',
            default => 'd',
        }, $this->read(8));

        return $double;
    }

    /**
     * @param Endianness|null $endianness
     *
     * @return \DateTimeInterface
     */
    public function timestamp(Endianness $endianness = null): \DateTimeInterface
    {
        $timestamp = $this->uint32($endianness);

        return (new \DateTimeImmutable())->setTimestamp($timestamp);
    }

    /**
     * @param positive-int $size
     * @param Type $type
     * @param Endianness|null $endianness
     *
     * @return array<float|int>
     */
    public function array(
        int $size,
        Type $type,
        Endianness $endianness = null,
    ): array {
        $result = [];

        $endianness ??= $this->endianness;
        for ($i = 0; $i < $size; ++$i) {
            $result[] = $this->readAs($type, $endianness);
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
     * @throws NonReadableException
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

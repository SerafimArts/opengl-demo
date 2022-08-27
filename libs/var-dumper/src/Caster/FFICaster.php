<?php

declare(strict_types=1);

namespace Bic\VarDumper\Caster;

use FFI\CData;
use FFI\CType;

abstract class FFICaster
{
    private const KIND_SCALARS = [
        CType::TYPE_FLOAT,
        CType::TYPE_DOUBLE,
        CType::TYPE_UINT8,
        CType::TYPE_SINT8,
        CType::TYPE_UINT16,
        CType::TYPE_SINT16,
        CType::TYPE_UINT32,
        CType::TYPE_SINT32,
        CType::TYPE_UINT64,
        CType::TYPE_SINT64,
        CType::TYPE_BOOL,
        CType::TYPE_CHAR,
    ];

    /**
     * @param CData $data
     * @return int
     */
    protected static function handleOf(CData $data): int
    {
        return \FFI::cast('ptrdiff_t', \FFI::addr($data))->cdata;
    }

    /**
     * @param CType $type
     * @return bool
     */
    protected static function isScalar(CType $type): bool
    {
        $scalars = self::KIND_SCALARS;

        if (\defined('\FFI\CType::TYPE_LONGDOUBLE')) {
            $scalars[] = CType::TYPE_LONGDOUBLE;
        }

        return \in_array($type->getKind(), $scalars, true);
    }

    /**
     * @param CType $type
     * @return bool
     */
    protected static function isEnum(CType $type): bool
    {
        return $type->getKind() === CType::TYPE_ENUM;
    }

    /**
     * @param CType $type
     * @return bool
     */
    protected static function isArray(CType $type): bool
    {
        return $type->getKind() === CType::TYPE_ARRAY;
    }

    /**
     * @param CType $type
     * @return bool
     */
    protected static function isPointer(CType $type): bool
    {
        return $type->getKind() === CType::TYPE_POINTER;
    }

    /**
     * @param CType $type
     * @return bool
     */
    protected static function isFunction(CType $type): bool
    {
        return $type->getKind() === CType::TYPE_FUNC;
    }

    /**
     * @param CType $type
     * @return bool
     */
    protected static function isStruct(CType $type): bool
    {
        return self::isStructLike($type)
            && ($type->getAttributes() & CType::ATTR_UNION) !== CType::ATTR_UNION;
    }

    /**
     * @param CType $type
     * @return bool
     */
    protected static function isUnion(CType $type): bool
    {
        return self::isStructLike($type)
            && ($type->getAttributes() & CType::ATTR_UNION) === CType::ATTR_UNION;
    }

    /**
     * @param CType $type
     * @return bool
     */
    protected static function isStructLike(CType $type): bool
    {
        return $type->getKind() === CType::TYPE_STRUCT;
    }

    /**
     * @param CType $type
     * @return bool
     */
    protected static function isPointerToStructLike(CType $type): bool
    {
        if ($type->getKind() !== CType::TYPE_POINTER) {
            return false;
        }

        $reference = $type->getPointerType();

        if ($reference->getKind() === CType::TYPE_POINTER) {
            return self::isPointerToStructLike($reference);
        }

        return self::isStructLike($reference);
    }

    /**
     * @param CType $function
     * @return non-empty-string
     */
    protected static function funcAbiToString(CType $function): string
    {
        return match ($function->getFuncABI()) {
            CType::ABI_DEFAULT, CType::ABI_CDECL => 'cdecl',
            CType::ABI_FASTCALL => 'fastcall',
            CType::ABI_THISCALL => 'thiscall',
            CType::ABI_STDCALL => 'stdcall',
            CType::ABI_PASCAL => 'pascal',
            CType::ABI_REGISTER => 'register',
            CType::ABI_MS => 'ms',
            CType::ABI_SYSV => 'sysv',
            CType::ABI_VECTORCALL => 'vectorcall',
            default => 'unknown'
        };
    }

    /**
     * @param CType $type
     * @return string
     */
    protected static function funcToString(CType $type): string
    {
        $arguments = [];

        for ($i = 0, $count = $type->getFuncParameterCount(); $i < $count; ++$i) {
            $param = $type->getFuncParameterType($i);

            $arguments[] = $param->getName();
        }

        $returnType = $type->getFuncReturnType();

        return \vsprintf('[%s] callable(%s): %s', [
            self::funcAbiToString($type),
            \implode(', ', $arguments),
            $returnType->getName(),
        ]);
    }
}

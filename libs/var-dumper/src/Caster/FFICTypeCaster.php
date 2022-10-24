<?php

declare(strict_types=1);

namespace Bic\VarDumper\Caster;

use FFI\CType;
use Symfony\Component\VarDumper\Caster\Caster;
use Symfony\Component\VarDumper\Cloner\Stub;

/**
 * @package dumper
 */
final class FFICTypeCaster extends FFICaster
{
    public static function castCType(CType $type, array $args, Stub $stub): array
    {
        $stub->class = $type->getName();

        return match (true) {
            self::isScalar($type),
            self::isEnum($type) => [],
            self::isPointer($type) => [Caster::PREFIX_VIRTUAL . '0' => $type->getPointerType()],
            self::isFunction($type) => self::castFFIFunction($stub, $type),
            self::isStruct($type) => self::castFFIStruct($type),
            self::isUnion($type) => self::castFFIUnion($type),
            default => $args,
        };
    }

    private static function castFFIUnion(CType $type): array
    {
        $result = [];
        foreach ($type->getStructFieldNames() as $name) {
            $result[Caster::PREFIX_VIRTUAL . $name . '?'] = $type->getStructFieldType($name);
        }

        return $result;
    }

    private static function castFFIStruct(CType $type): array
    {
        $result = [];

        foreach ($type->getStructFieldNames() as $name) {
            $result[Caster::PREFIX_VIRTUAL . $name] = $type->getStructFieldType($name);
        }

        return $result;
    }

    private static function castFFIFunction(Stub $stub, CType $type): array
    {
        $stub->class = self::funcToString($type);

        return [Caster::PREFIX_VIRTUAL . 'returnType' => $type->getFuncReturnType()];
    }
}

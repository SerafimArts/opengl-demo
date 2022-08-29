<?php

declare(strict_types=1);

namespace Bic\VarDumper\Caster;

use FFI\CData;
use FFI\CType;
use Symfony\Component\VarDumper\Caster\Caster;
use Symfony\Component\VarDumper\Cloner\Stub;

/**
 * @package dumper
 */
final class FFICDataCaster extends FFICaster
{
    public static function castCData(CData $data, array $args, Stub $stub): array
    {
        $type = \FFI::typeof($data);

        $stub->class = $type->getName();
        $stub->handle = 0;

        return match (true) {
            self::isScalar($type), self::isEnum($type) => [Caster::PREFIX_VIRTUAL . 'cdata' => $data->cdata],
            self::isPointer($type) => self::castFFIPointer($stub, $type, $data),
            self::isFunction($type) => self::castFFIFunction($stub, $type),
            self::isStructLike($type) => self::castFFIStructLike($stub, $type, $data),
            self::isArray($type) => self::castFFIArrayType($stub, $type, $data),
            default => $args,
        };
    }

    /**
     * @param CType $type
     * @param CData $data
     * @return array<string>
     */
    private static function arrayValues(CType $type, CData $data): array
    {
        $result = [];

        for($i = 0, $size = $type->getArrayLength(); $i < $size; ++$i) {
            $result[] = $data[$i];
        }

        return $result;
    }

    private static function castFFIArrayType(Stub $stub, CType $type, CData $data): array
    {
        $of = $type->getArrayElementType();

        if ($of->getKind() === CType::TYPE_CHAR) {
            $stub->type = Stub::TYPE_STRING;
            $stub->value = \implode('', self::arrayValues($type, $data));
            $stub->class = Stub::STRING_BINARY;

            return [];
        }

        $stub->type = Stub::TYPE_ARRAY;
        $stub->value = $of->getName();
        $stub->class = $type->getArrayLength();

        return self::arrayValues($type, $data);
    }

    private static function castFFIPointer(Stub $stub, CType $type, CData $data): array
    {
        $stub->type = Stub::TYPE_REF;

        $reference = $type->getPointerType();
        if ($reference->getKind() === CType::TYPE_CHAR) {
            $stub->class = '(unsafe access)';
            return [];
        }

        return match (true) {
            self::isStructLike($reference) => self::castFFIStructLike($stub, $reference, $data[0]),
            self::isFunction($reference) => self::castFFIFunction($stub, $reference),
            default => FFICTypeCaster::castCType($reference, [], $stub),
        };
    }

    private static function castFFIFunction(Stub $stub, CType $type): array
    {
        $stub->class = self::funcToString($type);

        return [Caster::PREFIX_VIRTUAL . 'returnType' => $type->getFuncReturnType()];
    }

    private static function castFFIStructLike(Stub $stub, CType $type, CData $data): array
    {
        $result = [];

        foreach ($type->getStructFieldNames() as $name) {
            $field = $type->getStructFieldType($name);

            if (self::isStructLike($field) || self::isPointerToStructLike($field)) {
                $result[Caster::PREFIX_VIRTUAL . $name] = $data->{$name};
            } else {
                $result[Caster::PREFIX_VIRTUAL . $name . '<' . $field->getName() . '>'] = $data->{$name};
            }
        }

        return $result;
    }
}

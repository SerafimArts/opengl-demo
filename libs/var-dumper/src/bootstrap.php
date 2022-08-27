<?php

declare(strict_types=1);

use Bic\VarDumper\Caster\FFICDataCaster;
use Bic\VarDumper\Caster\FFICTypeCaster;
use FFI\CData;
use FFI\CType;
use Symfony\Component\VarDumper\Cloner\AbstractCloner;

AbstractCloner::$defaultCasters[CType::class] = [FFICTypeCaster::class, 'castCType'];
AbstractCloner::$defaultCasters[CData::class] = [FFICDataCaster::class, 'castCData'];

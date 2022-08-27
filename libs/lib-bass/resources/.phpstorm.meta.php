<?php

namespace PHPSTORM_META {
    registerArgumentsSet('BassALTypes',
        'void *',

        'bool',

        'float',
        'double',
        'long double',

        'char',
        'signed char',
        'unsigned char',
        'int',
        'signed int',
        'unsigned int',
        'long',
        'signed long',
        'unsigned long',
        'long long',
        'signed long long',
        'unsigned long long',

        'intptr_t',
        'uintptr_t',
        'size_t',
        'ssize_t',
        'ptrdiff_t',
        'off_t',
        'va_list',
        '__builtin_va_list',
        '__gnuc_va_list',

        // stdint.h
        'int8_t',
        'uint8_t',
        'int16_t',
        'uint16_t',
        'int32_t',
        'uint32_t',
        'int64_t',
        'uint64_t',

        // bass.h
        'HWND',
        'GUID',
        'BASS_DEVICEINFO',
        'BASS_INFO',
        'BASS_RECORDINFO',
        'BASS_SAMPLE',
        'BASS_CHANNELINFO',
        'BASS_PLUGINFORM',
        'BASS_PLUGININFO',
        'BASS_3DVECTOR',
        'BASS_FILEPROCS',
        'TAG_ID3',
        'TAG_APE_BINARY',
        'TAG_BEXT',
        'TAG_CART_TIMER',
        'TAG_CART',
        'TAG_CUE_POINT',
        'TAG_CUE',
        'TAG_SMPL_LOOP',
        'TAG_SMPL',
        'TAG_CA_CODEC',
        'WAVEFORMATEX',
        'BASS_DX8_CHORUS',
        'BASS_DX8_COMPRESSOR',
        'BASS_DX8_DISTORTION',
        'BASS_DX8_ECHO',
        'BASS_DX8_FLANGER',
        'BASS_DX8_GARGLE',
        'BASS_DX8_I3DL2REVERB',
        'BASS_DX8_PARAMEQ',
        'BASS_DX8_REVERB',
        'BASS_FX_VOLUME_PARAM'
    );

    override(\FFI\Proxy\ApiAwareTrait::new(), map([
        '' => '@',
        '' => '\Bic\Lib\BassAL\CData\@',
    ]));

    expectedArguments(\Bic\Lib\BassAL::new(), 0, argumentsSet('BassALTypes'));
    expectedArguments(\Bic\Lib\BassAL::cast(), 0, argumentsSet('BassALTypes'));
    expectedArguments(\Bic\Lib\BassAL::type(), 0, argumentsSet('BassALTypes'));
}

<?php

/**
 * This file is part of Bic Engine package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Bic\Lib\BassAL\CData;

use FFI\CData;

/**
 * @internal This is autocompletion stub class for FFI struct
 */
final class HWND extends CData
{
    public int $i;
}

/**
 * @internal This is autocompletion stub class for FFI struct
 */
final class GUID extends CData
{
    public int $Data1;
    public int $Data2;
    public int $Data3;
    /** @var array{int, int, int, int, int, int, int, int } */
    public array $Data4;
}

/**
 * @internal This is autocompletion stub class for FFI struct
 */
final class BASS_DEVICEINFO extends CData
{
    public string $name;
    public string $driver;
    public int $flags;
}

/**
 * @internal This is autocompletion stub class for FFI struct
 */
final class BASS_INFO extends CData
{
    public int $flags;
    public int $hwsize;
    public int $hwfree;
    public int $freesam;
    public int $free3d;
    public int $minrate;
    public int $maxrate;
    public int $eax;
    public int $minbuf;
    public int $dsver;
    public int $latency;
    public int $initflags;
    public int $speakers;
    public int $freq;
}

/**
 * @internal This is autocompletion stub class for FFI struct
 */
final class BASS_RECORDINFO extends CData
{
    public int $flags;
    public int $formats;
    public int $inputs;
    public int $singlein;
    public int $freq;
}

/**
 * @internal This is autocompletion stub class for FFI struct
 */
final class BASS_SAMPLE extends CData
{
    public int $freq;
    public float $volume;
    public float $pan;
    public int $flags;
    public int $length;
    public int $max;
    public int $origres;
    public int $chans;
    public int $mingap;
    public int $mode3d;
    public float $mindist;
    public float $maxdist;
    public int $iangle;
    public int $oangle;
    public float $outvol;
    public int $vam;
    public int $priority;
}

/**
 * @internal This is autocompletion stub class for FFI struct
 */
final class BASS_CHANNELINFO extends CData
{
    public int $freq;
    public int $chans;
    public int $flags;
    public int $ctype;
    public int $origres;
    public int $plugin;
    public int $sample;
    public string $filename;
}

/**
 * @internal This is autocompletion stub class for FFI struct
 */
final class BASS_PLUGINFORM extends CData
{
    public int $ctype;
    public string $name;
    public string $exts;
}

/**
 * @internal This is autocompletion stub class for FFI struct
 */
final class BASS_PLUGININFO extends CData
{
    public int $version;
    public int $formatc;
    public BASS_PLUGINFORM $formats;
}

/**
 * @internal This is autocompletion stub class for FFI struct
 */
final class BASS_3DVECTOR extends CData
{
    public float $x;
    public float $y;
    public float $z;
}

/**
 * @internal This is autocompletion stub class for FFI struct
 */
final class BASS_FILEPROCS extends CData
{
    /** @var \Closure(mixed):void */
    public \Closure $close;
    /** @var \Closure(mixed):int */
    public \Closure $length;
    /** @var \Closure(mixed, int, mixed):int */
    public \Closure $read;
    /** @var \Closure(int, mixed):int */
    public \Closure $seek;
}

/**
 * @internal This is autocompletion stub class for FFI struct
 */
final class TAG_ID3 extends CData
{
    /** @var array<string> */
    public array $id;
    /** @var array<string> */
    public array $title;
    /** @var array<string> */
    public array $artist;
    /** @var array<string> */
    public array $album;
    /** @var array<string> */
    public array $year;
    /** @var array<string> */
    public array $comment;
    public int $genre;
}

/**
 * @internal This is autocompletion stub class for FFI struct
 */
final class TAG_APE_BINARY extends CData
{
    public string $key;
    public CData $data;
    public int $length;
}

/**
 * @internal This is autocompletion stub class for FFI struct
 */
final class TAG_BEXT extends CData
{
    /** @var array<string> */
    public array $Description;
    /** @var array<string> */
    public array $Originator;
    /** @var array<string> */
    public array $OriginatorReference;
    /** @var array<string> */
    public array $OriginationDate;
    /** @var array<string> */
    public array $OriginationTime;
    public int $TimeReference;
    public int $Version;
    /** @var array<int> */
    public array $UMID;
    /** @var array<int> */
    public array $Reserved;
    /** @var array<string> */
    public array $CodingHistory;
}

/**
 * @internal This is autocompletion stub class for FFI struct
 */
final class TAG_CART_TIMER extends CData
{
    public int $dwUsage;
    public int $dwValue;
}

/**
 * @internal This is autocompletion stub class for FFI struct
 */
final class TAG_CART extends CData
{
    /** @var array<string> */
    public array $Versio;
    /** @var array<string> */
    public array $Title;
    /** @var array<string> */
    public array $Artist;
    /** @var array<string> */
    public array $CutID;
    /** @var array<string> */
    public array $ClientID;
    /** @var array<string> */
    public array $Category;
    /** @var array<string> */
    public array $Classification;
    /** @var array<string> */
    public array $OutCue;
    /** @var array<string> */
    public array $StartDate;
    /** @var array<string> */
    public array $StartTim;
    /** @var array<string> */
    public array $EndDate;
    /** @var array<string> */
    public array $EndTime;
    /** @var array<string> */
    public array $ProducerAppID;
    /** @var array<string> */
    public array $ProducerAppVersion;
    /** @var array<string> */
    public array $UserDef;
    public int $dwLevelReference;
    /** @var array<TAG_CART_TIMER> */
    public array $PostTimer;
    /** @var array<string> */
    public array $Reserved;
    /** @var array<string> */
    public array $URL;
    /** @var array<string> */
    public array $TagText;
}

/**
 * @internal This is autocompletion stub class for FFI struct
 */
final class TAG_CUE_POINT extends CData
{
    public int $dwName;
    public int $dwPosition;
    public int $fccChunk;
    public int $dwChunkStart;
    public int $dwBlockStart;
    public int $dwSampleOffset;
}

/**
 * @internal This is autocompletion stub class for FFI struct
 */
final class TAG_CUE extends CData
{
    public int $dwCuePoints;
    /** @var array<TAG_CUE_POINT> */
    public array $CuePoints;
}

/**
 * @internal This is autocompletion stub class for FFI struct
 */
final class TAG_SMPL_LOOP extends CData
{
    public int $dwIdentifier;
    public int $dwType;
    public int $dwStart;
    public int $dwEnd;
    public int $dwFraction;
    public int $dwPlayCount;
}

/**
 * @internal This is autocompletion stub class for FFI struct
 */
final class TAG_SMPL extends CData
{
    public int $dwManufacturer;
    public int $dwProduct;
    public int $dwSamplePeriod;
    public int $dwMIDIUnityNote;
    public int $dwMIDIPitchFraction;
    public int $dwSMPTEFormat;
    public int $dwSMPTEOffset;
    public int $cSampleLoops;
    public int $cbSamplerData;
    /** @var array<TAG_SMPL_LOOP> */
    public array $SampleLoops;
}

/**
 * @internal This is autocompletion stub class for FFI struct
 */
final class TAG_CA_CODEC extends CData
{
    public int $ftype;
    public int $atype;
    public string $name;
}

/**
 * @internal This is autocompletion stub class for FFI struct
 */
final class WAVEFORMATEX extends CData
{
    public int $wFormatTag;
    public int $nChannels;
    public int $nSamplesPerSec;
    public int $nAvgBytesPerSec;
    public int $nBlockAlign;
    public int $wBitsPerSample;
    public int $cbSize;
}

/**
 * @internal This is autocompletion stub class for FFI struct
 */
final class BASS_DX8_CHORUS extends CData
{
    public float $fWetDryMix;
    public float $fDepth;
    public float $fFeedback;
    public float $fFrequency;
    public int $lWaveform;
    public float $fDelay;
    public int $lPhase;
}

/**
 * @internal This is autocompletion stub class for FFI struct
 */
final class BASS_DX8_COMPRESSOR extends CData
{
    public float $fGain;
    public float $fAttack;
    public float $fRelease;
    public float $fThreshold;
    public float $fRatio;
    public float $fPredelay;
}

/**
 * @internal This is autocompletion stub class for FFI struct
 */
final class BASS_DX8_DISTORTION extends CData
{
    public float $fGain;
    public float $fEdge;
    public float $fPostEQCenterFrequency;
    public float $fPostEQBandwidth;
    public float $fPreLowpassCutoff;
}

/**
 * @internal This is autocompletion stub class for FFI struct
 */
final class BASS_DX8_ECHO extends CData
{
    public float $fWetDryMix;
    public float $fFeedback;
    public float $fLeftDelay;
    public float $fRightDelay;
    public int $lPanDelay;
}

/**
 * @internal This is autocompletion stub class for FFI struct
 */
final class BASS_DX8_FLANGER extends CData
{
    public float $fWetDryMix;
    public float $fDepth;
    public float $fFeedback;
    public float $fFrequency;
    public int $lWaveform;
    public float $fDelay;
    public int $lPhase;
}

/**
 * @internal This is autocompletion stub class for FFI struct
 */
final class BASS_DX8_GARGLE extends CData
{
    public int $dwRateHz;
    public int $dwWaveShape;
}

/**
 * @internal This is autocompletion stub class for FFI struct
 */
final class BASS_DX8_I3DL2REVERB extends CData
{
    public int $lRoom;
    public int $lRoomHF;
    public float $flRoomRolloffFactor;
    public float $flDecayTime;
    public float $flDecayHFRatio;
    public int $lReflections;
    public float $flReflectionsDelay;
    public int $lReverb;
    public float $flReverbDelay;
    public float $flDiffusion;
    public float $flDensity;
    public float $flHFReference;
}

/**
 * @internal This is autocompletion stub class for FFI struct
 */
final class BASS_DX8_PARAMEQ extends CData
{
    public float $fCenter;
    public float $fBandwidth;
    public float $fGain;
}

/**
 * @internal This is autocompletion stub class for FFI struct
 */
final class BASS_DX8_REVERB extends CData
{
    public float $fInGain;
    public float $fReverbMix;
    public float $fReverbTime;
    public float $fHighFreqRTRatio;
}

/**
 * @internal This is autocompletion stub class for FFI struct
 */
final class BASS_FX_VOLUME_PARAM extends CData
{
    public float $fTarget;
    public float $fCurrent;
    public float $fTime;
    public int $lCurve;
}

/**
 * @internal This is autocompletion stub class for FFI struct
 */
final class BASS_MIXER_NODE extends CData
{
    public int $pos;
    public float $value;
}

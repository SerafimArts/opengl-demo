<?php

declare(strict_types=1);

namespace Bic\Lib;

use Bic\Lib\BassAL\Exception\LoadingException;
use Bic\Lib\BassAL\Exception\PlatformException;
use Bic\Lib\BassAL\Platform;
use FFI\Contracts\Headers\HeaderInterface;
use FFI\Contracts\Preprocessor\Exception\DirectiveDefinitionExceptionInterface;
use FFI\Env\Exception\EnvironmentException;
use FFI\Env\Runtime;
use FFI\Headers\Bass as BassALHeaders;
use FFI\Headers\Bass\Platform as HeadersPlatform;
use FFI\Headers\Bass\Version as HeadersVersion;
use FFI\Location\Locator;
use FFI\Proxy\Proxy;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;

final class BassAL extends Proxy
{
    public const BASS_OK                        = 0;
    public const BASS_ERROR_MEM                 = 1;
    public const BASS_ERROR_FILEOPEN            = 2;
    public const BASS_ERROR_DRIVER              = 3;
    public const BASS_ERROR_BUFLOST             = 4;
    public const BASS_ERROR_HANDLE              = 5;
    public const BASS_ERROR_FORMAT              = 6;
    public const BASS_ERROR_POSITION            = 7;
    public const BASS_ERROR_INIT                = 8;
    public const BASS_ERROR_START               = 9;
    public const BASS_ERROR_SSL                 = 10;
    public const BASS_ERROR_REINIT              = 11;
    public const BASS_ERROR_ALREADY             = 14;
    public const BASS_ERROR_NOTAUDIO            = 17;
    public const BASS_ERROR_NOCHAN              = 18;
    public const BASS_ERROR_ILLTYPE             = 19;
    public const BASS_ERROR_ILLPARAM            = 20;
    public const BASS_ERROR_NO3D                = 21;
    public const BASS_ERROR_NOEAX               = 22;
    public const BASS_ERROR_DEVICE              = 23;
    public const BASS_ERROR_NOPLAY              = 24;
    public const BASS_ERROR_FREQ                = 25;
    public const BASS_ERROR_NOTFILE             = 27;
    public const BASS_ERROR_NOHW                = 29;
    public const BASS_ERROR_EMPTY               = 31;
    public const BASS_ERROR_NONET               = 32;
    public const BASS_ERROR_CREATE              = 33;
    public const BASS_ERROR_NOFX                = 34;
    public const BASS_ERROR_NOTAVAIL            = 37;
    public const BASS_ERROR_DECODE              = 38;
    public const BASS_ERROR_DX                  = 39;
    public const BASS_ERROR_TIMEOUT             = 40;
    public const BASS_ERROR_FILEFORM            = 41;
    public const BASS_ERROR_SPEAKER             = 42;
    public const BASS_ERROR_VERSION             = 43;
    public const BASS_ERROR_CODEC               = 44;
    public const BASS_ERROR_ENDED               = 45;
    public const BASS_ERROR_BUSY                = 46;
    public const BASS_ERROR_UNSTREAMABLE        = 47;
    public const BASS_ERROR_PROTOCOL            = 48;
    public const BASS_ERROR_UNKNOWN             = -1;
    public const BASS_CONFIG_BUFFER             = 0;
    public const BASS_CONFIG_UPDATEPERIOD       = 1;
    public const BASS_CONFIG_GVOL_SAMPLE        = 4;
    public const BASS_CONFIG_GVOL_STREAM        = 5;
    public const BASS_CONFIG_GVOL_MUSIC         = 6;
    public const BASS_CONFIG_CURVE_VOL          = 7;
    public const BASS_CONFIG_CURVE_PAN          = 8;
    public const BASS_CONFIG_FLOATDSP           = 9;
    public const BASS_CONFIG_3DALGORITHM        = 10;
    public const BASS_CONFIG_NET_TIMEOUT        = 11;
    public const BASS_CONFIG_NET_BUFFER         = 12;
    public const BASS_CONFIG_PAUSE_NOPLAY       = 13;
    public const BASS_CONFIG_NET_PREBUF         = 15;
    public const BASS_CONFIG_NET_PASSIVE        = 18;
    public const BASS_CONFIG_REC_BUFFER         = 19;
    public const BASS_CONFIG_NET_PLAYLIST       = 21;
    public const BASS_CONFIG_MUSIC_VIRTUAL      = 22;
    public const BASS_CONFIG_VERIFY             = 23;
    public const BASS_CONFIG_UPDATETHREADS      = 24;
    public const BASS_CONFIG_DEV_BUFFER         = 27;
    public const BASS_CONFIG_REC_LOOPBACK       = 28;
    public const BASS_CONFIG_VISTA_TRUEPOS      = 30;
    public const BASS_CONFIG_IOS_SESSION        = 34;
    public const BASS_CONFIG_IOS_MIXAUDIO       = 34;
    public const BASS_CONFIG_DEV_DEFAULT        = 36;
    public const BASS_CONFIG_NET_READTIMEOUT    = 37;
    public const BASS_CONFIG_VISTA_SPEAKERS     = 38;
    public const BASS_CONFIG_IOS_SPEAKER        = 39;
    public const BASS_CONFIG_MF_DISABLE         = 40;
    public const BASS_CONFIG_HANDLES            = 41;
    public const BASS_CONFIG_UNICODE            = 42;
    public const BASS_CONFIG_SRC                = 43;
    public const BASS_CONFIG_SRC_SAMPLE         = 44;
    public const BASS_CONFIG_ASYNCFILE_BUFFER   = 45;
    public const BASS_CONFIG_OGG_PRESCAN        = 47;
    public const BASS_CONFIG_MF_VIDEO           = 48;
    public const BASS_CONFIG_AIRPLAY            = 49;
    public const BASS_CONFIG_DEV_NONSTOP        = 50;
    public const BASS_CONFIG_IOS_NOCATEGORY     = 51;
    public const BASS_CONFIG_VERIFY_NET         = 52;
    public const BASS_CONFIG_DEV_PERIOD         = 53;
    public const BASS_CONFIG_FLOAT              = 54;
    public const BASS_CONFIG_NET_SEEK           = 56;
    public const BASS_CONFIG_AM_DISABLE         = 58;
    public const BASS_CONFIG_NET_PLAYLIST_DEPTH = 59;
    public const BASS_CONFIG_NET_PREBUF_WAIT    = 60;
    public const BASS_CONFIG_ANDROID_SESSIONID  = 62;
    public const BASS_CONFIG_WASAPI_PERSIST     = 65;
    public const BASS_CONFIG_REC_WASAPI         = 66;
    public const BASS_CONFIG_ANDROID_AAUDIO     = 67;
    public const BASS_CONFIG_SAMPLE_ONEHANDLE   = 69;
    public const BASS_CONFIG_DEV_TIMEOUT        = 70;
    public const BASS_CONFIG_NET_META           = 71;
    public const BASS_CONFIG_NET_RESTRATE       = 72;
    public const BASS_CONFIG_NET_AGENT          = 16;
    public const BASS_CONFIG_NET_PROXY          = 17;
    public const BASS_CONFIG_IOS_NOTIFY         = 46;
    public const BASS_CONFIG_LIBSSL             = 64;
    public const BASS_CONFIG_THREAD             = 0x40000000;
    public const BASS_IOS_SESSION_MIX           = 1;
    public const BASS_IOS_SESSION_DUCK          = 2;
    public const BASS_IOS_SESSION_AMBIENT       = 4;
    public const BASS_IOS_SESSION_SPEAKER       = 8;
    public const BASS_IOS_SESSION_DISABLE       = 16;
    public const BASS_DEVICE_8BITS              = 1;
    public const BASS_DEVICE_MONO               = 2;
    public const BASS_DEVICE_3D                 = 4;
    public const BASS_DEVICE_16BITS             = 8;
    public const BASS_DEVICE_REINIT             = 128;
    public const BASS_DEVICE_LATENCY            = 0x100;
    public const BASS_DEVICE_CPSPEAKERS         = 0x400;
    public const BASS_DEVICE_SPEAKERS           = 0x800;
    public const BASS_DEVICE_NOSPEAKER          = 0x1000;
    public const BASS_DEVICE_DMIX               = 0x2000;
    public const BASS_DEVICE_FREQ               = 0x4000;
    public const BASS_DEVICE_STEREO             = 0x8000;
    public const BASS_DEVICE_HOG                = 0x10000;
    public const BASS_DEVICE_AUDIOTRACK         = 0x20000;
    public const BASS_DEVICE_DSOUND             = 0x40000;
    public const BASS_DEVICE_SOFTWARE           = 0x80000;
    public const BASS_OBJECT_DS                 = 1;
    public const BASS_OBJECT_DS3DL              = 2;
    public const BASS_DEVICE_ENABLED            = 1;
    public const BASS_DEVICE_DEFAULT            = 2;
    public const BASS_DEVICE_INIT               = 4;
    public const BASS_DEVICE_LOOPBACK           = 8;
    public const BASS_DEVICE_DEFAULTCOM         = 128;
    public const BASS_DEVICE_TYPE_MASK          = 0xff000000;
    public const BASS_DEVICE_TYPE_NETWORK       = 0x01000000;
    public const BASS_DEVICE_TYPE_SPEAKERS      = 0x02000000;
    public const BASS_DEVICE_TYPE_LINE          = 0x03000000;
    public const BASS_DEVICE_TYPE_HEADPHONES    = 0x04000000;
    public const BASS_DEVICE_TYPE_MICROPHONE    = 0x05000000;
    public const BASS_DEVICE_TYPE_HEADSET       = 0x06000000;
    public const BASS_DEVICE_TYPE_HANDSET       = 0x07000000;
    public const BASS_DEVICE_TYPE_DIGITAL       = 0x08000000;
    public const BASS_DEVICE_TYPE_SPDIF         = 0x09000000;
    public const BASS_DEVICE_TYPE_HDMI          = 0x0a000000;
    public const BASS_DEVICE_TYPE_DISPLAYPORT   = 0x40000000;
    public const BASS_DEVICES_AIRPLAY           = 0x1000000;
    public const DSCAPS_EMULDRIVER              = 0x00000020;
    public const DSCAPS_CERTIFIED               = 0x00000040;
    public const DSCAPS_HARDWARE                = 0x80000000;
    public const DSCCAPS_EMULDRIVER             = self::DSCAPS_EMULDRIVER;
    public const DSCCAPS_CERTIFIED              = self::DSCAPS_CERTIFIED;
    public const WAVE_FORMAT_1M08               = 0x00000001;
    public const WAVE_FORMAT_1S08               = 0x00000002;
    public const WAVE_FORMAT_1M16               = 0x00000004;
    public const WAVE_FORMAT_1S16               = 0x00000008;
    public const WAVE_FORMAT_2M08               = 0x00000010;
    public const WAVE_FORMAT_2S08               = 0x00000020;
    public const WAVE_FORMAT_2M16               = 0x00000040;
    public const WAVE_FORMAT_2S16               = 0x00000080;
    public const WAVE_FORMAT_4M08               = 0x00000100;
    public const WAVE_FORMAT_4S08               = 0x00000200;
    public const WAVE_FORMAT_4M16               = 0x00000400;
    public const WAVE_FORMAT_4S16               = 0x00000800;
    public const BASS_SAMPLE_8BITS              = 1;
    public const BASS_SAMPLE_FLOAT              = 256;
    public const BASS_SAMPLE_MONO               = 2;
    public const BASS_SAMPLE_LOOP               = 4;
    public const BASS_SAMPLE_3D                 = 8;
    public const BASS_SAMPLE_SOFTWARE           = 16;
    public const BASS_SAMPLE_MUTEMAX            = 32;
    public const BASS_SAMPLE_VAM                = 64;
    public const BASS_SAMPLE_FX                 = 128;
    public const BASS_SAMPLE_OVER_VOL           = 0x10000;
    public const BASS_SAMPLE_OVER_POS           = 0x20000;
    public const BASS_SAMPLE_OVER_DIST          = 0x30000;
    public const BASS_STREAM_PRESCAN            = 0x20000;
    public const BASS_STREAM_AUTOFREE           = 0x40000;
    public const BASS_STREAM_RESTRATE           = 0x80000;
    public const BASS_STREAM_BLOCK              = 0x100000;
    public const BASS_STREAM_DECODE             = 0x200000;
    public const BASS_STREAM_STATUS             = 0x800000;
    public const BASS_MP3_IGNOREDELAY           = 0x200;
    public const BASS_MP3_SETPOS                = self::BASS_STREAM_PRESCAN;
    public const BASS_MUSIC_FLOAT               = self::BASS_SAMPLE_FLOAT;
    public const BASS_MUSIC_MONO                = self::BASS_SAMPLE_MONO;
    public const BASS_MUSIC_LOOP                = self::BASS_SAMPLE_LOOP;
    public const BASS_MUSIC_3D                  = self::BASS_SAMPLE_3D;
    public const BASS_MUSIC_FX                  = self::BASS_SAMPLE_FX;
    public const BASS_MUSIC_AUTOFREE            = self::BASS_STREAM_AUTOFREE;
    public const BASS_MUSIC_DECODE              = self::BASS_STREAM_DECODE;
    public const BASS_MUSIC_PRESCAN             = self::BASS_STREAM_PRESCAN;
    public const BASS_MUSIC_CALCLEN             = self::BASS_MUSIC_PRESCAN;
    public const BASS_MUSIC_RAMP                = 0x200;
    public const BASS_MUSIC_RAMPS               = 0x400;
    public const BASS_MUSIC_SURROUND            = 0x800;
    public const BASS_MUSIC_SURROUND2           = 0x1000;
    public const BASS_MUSIC_FT2PAN              = 0x2000;
    public const BASS_MUSIC_FT2MOD              = 0x2000;
    public const BASS_MUSIC_PT1MOD              = 0x4000;
    public const BASS_MUSIC_NONINTER            = 0x10000;
    public const BASS_MUSIC_SINCINTER           = 0x800000;
    public const BASS_MUSIC_POSRESET            = 0x8000;
    public const BASS_MUSIC_POSRESETEX          = 0x400000;
    public const BASS_MUSIC_STOPBACK            = 0x80000;
    public const BASS_MUSIC_NOSAMPLE            = 0x100000;
    public const BASS_SPEAKER_FRONT             = 0x1000000;
    public const BASS_SPEAKER_REAR              = 0x2000000;
    public const BASS_SPEAKER_CENLFE            = 0x3000000;
    public const BASS_SPEAKER_REAR2             = 0x4000000;
    public const BASS_SPEAKER_LEFT              = 0x10000000;
    public const BASS_SPEAKER_RIGHT             = 0x20000000;
    public const BASS_SPEAKER_FRONTLEFT         = self::BASS_SPEAKER_FRONT | self::BASS_SPEAKER_LEFT;
    public const BASS_SPEAKER_FRONTRIGHT        = self::BASS_SPEAKER_FRONT | self::BASS_SPEAKER_RIGHT;
    public const BASS_SPEAKER_REARLEFT          = self::BASS_SPEAKER_REAR | self::BASS_SPEAKER_LEFT;
    public const BASS_SPEAKER_REARRIGHT         = self::BASS_SPEAKER_REAR | self::BASS_SPEAKER_RIGHT;
    public const BASS_SPEAKER_CENTER            = self::BASS_SPEAKER_CENLFE | self::BASS_SPEAKER_LEFT;
    public const BASS_SPEAKER_LFE               = self::BASS_SPEAKER_CENLFE | self::BASS_SPEAKER_RIGHT;
    public const BASS_SPEAKER_REAR2LEFT         = self::BASS_SPEAKER_REAR2 | self::BASS_SPEAKER_LEFT;
    public const BASS_SPEAKER_REAR2RIGHT        = self::BASS_SPEAKER_REAR2 | self::BASS_SPEAKER_RIGHT;
    public const BASS_ASYNCFILE                 = 0x40000000;
    public const BASS_UNICODE                   = 0x80000000;
    public const BASS_RECORD_PAUSE              = 0x8000;
    public const BASS_RECORD_ECHOCANCEL         = 0x2000;
    public const BASS_RECORD_AGC                = 0x4000;
    public const BASS_VAM_HARDWARE              = 1;
    public const BASS_VAM_SOFTWARE              = 2;
    public const BASS_VAM_TERM_TIME             = 4;
    public const BASS_VAM_TERM_DIST             = 8;
    public const BASS_VAM_TERM_PRIO             = 16;
    public const BASS_ORIGRES_FLOAT             = 0x10000;
    public const BASS_CTYPE_SAMPLE              = 1;
    public const BASS_CTYPE_RECORD              = 2;
    public const BASS_CTYPE_STREAM              = 0x10000;
    public const BASS_CTYPE_STREAM_VORBIS       = 0x10002;
    public const BASS_CTYPE_STREAM_OGG          = 0x10002;
    public const BASS_CTYPE_STREAM_MP1          = 0x10003;
    public const BASS_CTYPE_STREAM_MP2          = 0x10004;
    public const BASS_CTYPE_STREAM_MP3          = 0x10005;
    public const BASS_CTYPE_STREAM_AIFF         = 0x10006;
    public const BASS_CTYPE_STREAM_CA           = 0x10007;
    public const BASS_CTYPE_STREAM_MF           = 0x10008;
    public const BASS_CTYPE_STREAM_AM           = 0x10009;
    public const BASS_CTYPE_STREAM_SAMPLE       = 0x1000a;
    public const BASS_CTYPE_STREAM_DUMMY        = 0x18000;
    public const BASS_CTYPE_STREAM_DEVICE       = 0x18001;
    public const BASS_CTYPE_STREAM_WAV          = 0x40000;
    public const BASS_CTYPE_STREAM_WAV_PCM      = 0x50001;
    public const BASS_CTYPE_STREAM_WAV_FLOAT    = 0x50003;
    public const BASS_CTYPE_MUSIC_MOD           = 0x20000;
    public const BASS_CTYPE_MUSIC_MTM           = 0x20001;
    public const BASS_CTYPE_MUSIC_S3M           = 0x20002;
    public const BASS_CTYPE_MUSIC_XM            = 0x20003;
    public const BASS_CTYPE_MUSIC_IT            = 0x20004;
    public const BASS_CTYPE_MUSIC_MO3           = 0x00100;
    public const BASS_3DMODE_NORMAL             = 0;
    public const BASS_3DMODE_RELATIVE           = 1;
    public const BASS_3DMODE_OFF                = 2;
    public const BASS_3DALG_DEFAULT             = 0;
    public const BASS_3DALG_OFF                 = 1;
    public const BASS_3DALG_FULL                = 2;
    public const BASS_3DALG_LIGHT               = 3;
    public const BASS_SAMCHAN_NEW               = 1;
    public const BASS_SAMCHAN_STREAM            = 2;
    public const BASS_STREAMPROC_END            = 0x80000000;
    public const STREAMPROC_DUMMY               = 0;
    public const STREAMPROC_PUSH                = -1;
    public const STREAMPROC_DEVICE              = -2;
    public const STREAMPROC_DEVICE_3D           = -3;
    public const STREAMFILE_NOBUFFER            = 0;
    public const STREAMFILE_BUFFER              = 1;
    public const STREAMFILE_BUFFERPUSH          = 2;
    public const BASS_FILEDATA_END              = 0;
    public const BASS_FILEPOS_CURRENT           = 0;
    public const BASS_FILEPOS_DECODE            = self::BASS_FILEPOS_CURRENT;
    public const BASS_FILEPOS_DOWNLOAD          = 1;
    public const BASS_FILEPOS_END               = 2;
    public const BASS_FILEPOS_START             = 3;
    public const BASS_FILEPOS_CONNECTED         = 4;
    public const BASS_FILEPOS_BUFFER            = 5;
    public const BASS_FILEPOS_SOCKET            = 6;
    public const BASS_FILEPOS_ASYNCBUF          = 7;
    public const BASS_FILEPOS_SIZE              = 8;
    public const BASS_FILEPOS_BUFFERING         = 9;
    public const BASS_FILEPOS_AVAILABLE         = 10;
    public const BASS_SYNC_POS                  = 0;
    public const BASS_SYNC_END                  = 2;
    public const BASS_SYNC_META                 = 4;
    public const BASS_SYNC_SLIDE                = 5;
    public const BASS_SYNC_STALL                = 6;
    public const BASS_SYNC_DOWNLOAD             = 7;
    public const BASS_SYNC_FREE                 = 8;
    public const BASS_SYNC_SETPOS               = 11;
    public const BASS_SYNC_MUSICPOS             = 10;
    public const BASS_SYNC_MUSICINST            = 1;
    public const BASS_SYNC_MUSICFX              = 3;
    public const BASS_SYNC_OGG_CHANGE           = 12;
    public const BASS_SYNC_DEV_FAIL             = 14;
    public const BASS_SYNC_DEV_FORMAT           = 15;
    public const BASS_SYNC_THREAD               = 0x20000000;
    public const BASS_SYNC_MIXTIME              = 0x40000000;
    public const BASS_SYNC_ONETIME              = 0x80000000;
    public const BASS_ACTIVE_STOPPED            = 0;
    public const BASS_ACTIVE_PLAYING            = 1;
    public const BASS_ACTIVE_STALLED            = 2;
    public const BASS_ACTIVE_PAUSED             = 3;
    public const BASS_ACTIVE_PAUSED_DEVICE      = 4;
    public const BASS_ATTRIB_FREQ               = 1;
    public const BASS_ATTRIB_VOL                = 2;
    public const BASS_ATTRIB_PAN                = 3;
    public const BASS_ATTRIB_EAXMIX             = 4;
    public const BASS_ATTRIB_NOBUFFER           = 5;
    public const BASS_ATTRIB_VBR                = 6;
    public const BASS_ATTRIB_CPU                = 7;
    public const BASS_ATTRIB_SRC                = 8;
    public const BASS_ATTRIB_NET_RESUME         = 9;
    public const BASS_ATTRIB_SCANINFO           = 10;
    public const BASS_ATTRIB_NORAMP             = 11;
    public const BASS_ATTRIB_BITRATE            = 12;
    public const BASS_ATTRIB_BUFFER             = 13;
    public const BASS_ATTRIB_GRANULE            = 14;
    public const BASS_ATTRIB_USER               = 15;
    public const BASS_ATTRIB_TAIL               = 16;
    public const BASS_ATTRIB_PUSH_LIMIT         = 17;
    public const BASS_ATTRIB_MUSIC_AMPLIFY      = 0x100;
    public const BASS_ATTRIB_MUSIC_PANSEP       = 0x101;
    public const BASS_ATTRIB_MUSIC_PSCALER      = 0x102;
    public const BASS_ATTRIB_MUSIC_BPM          = 0x103;
    public const BASS_ATTRIB_MUSIC_SPEED        = 0x104;
    public const BASS_ATTRIB_MUSIC_VOL_GLOBAL   = 0x105;
    public const BASS_ATTRIB_MUSIC_ACTIVE       = 0x106;
    public const BASS_ATTRIB_MUSIC_VOL_CHAN     = 0x200;
    public const BASS_ATTRIB_MUSIC_VOL_INST     = 0x300;
    public const BASS_SLIDE_LOG                 = 0x1000000;
    public const BASS_DATA_AVAILABLE            = 0;
    public const BASS_DATA_NOREMOVE             = 0x10000000;
    public const BASS_DATA_FIXED                = 0x20000000;
    public const BASS_DATA_FLOAT                = 0x40000000;
    public const BASS_DATA_FFT256               = 0x80000000;
    public const BASS_DATA_FFT512               = 0x80000001;
    public const BASS_DATA_FFT1024              = 0x80000002;
    public const BASS_DATA_FFT2048              = 0x80000003;
    public const BASS_DATA_FFT4096              = 0x80000004;
    public const BASS_DATA_FFT8192              = 0x80000005;
    public const BASS_DATA_FFT16384             = 0x80000006;
    public const BASS_DATA_FFT32768             = 0x80000007;
    public const BASS_DATA_FFT_INDIVIDUAL       = 0x10;
    public const BASS_DATA_FFT_NOWINDOW         = 0x20;
    public const BASS_DATA_FFT_REMOVEDC         = 0x40;
    public const BASS_DATA_FFT_COMPLEX          = 0x80;
    public const BASS_DATA_FFT_NYQUIST          = 0x100;
    public const BASS_LEVEL_MONO                = 1;
    public const BASS_LEVEL_STEREO              = 2;
    public const BASS_LEVEL_RMS                 = 4;
    public const BASS_LEVEL_VOLPAN              = 8;
    public const BASS_LEVEL_NOREMOVE            = 16;
    public const BASS_TAG_ID3                   = 0;
    public const BASS_TAG_ID3V2                 = 1;
    public const BASS_TAG_OGG                   = 2;
    public const BASS_TAG_HTTP                  = 3;
    public const BASS_TAG_ICY                   = 4;
    public const BASS_TAG_META                  = 5;
    public const BASS_TAG_APE                   = 6;
    public const BASS_TAG_MP4                   = 7;
    public const BASS_TAG_WMA                   = 8;
    public const BASS_TAG_VENDOR                = 9;
    public const BASS_TAG_LYRICS3               = 10;
    public const BASS_TAG_CA_CODEC              = 11;
    public const BASS_TAG_MF                    = 13;
    public const BASS_TAG_WAVEFORMAT            = 14;
    public const BASS_TAG_AM_NAME               = 16;
    public const BASS_TAG_ID3V2_2               = 17;
    public const BASS_TAG_AM_MIME               = 18;
    public const BASS_TAG_LOCATION              = 19;
    public const BASS_TAG_RIFF_INFO             = 0x100;
    public const BASS_TAG_RIFF_BEXT             = 0x101;
    public const BASS_TAG_RIFF_CART             = 0x102;
    public const BASS_TAG_RIFF_DISP             = 0x103;
    public const BASS_TAG_RIFF_CUE              = 0x104;
    public const BASS_TAG_RIFF_SMPL             = 0x105;
    public const BASS_TAG_APE_BINARY            = 0x1000;
    public const BASS_TAG_MUSIC_NAME            = 0x10000;
    public const BASS_TAG_MUSIC_MESSAGE         = 0x10001;
    public const BASS_TAG_MUSIC_ORDERS          = 0x10002;
    public const BASS_TAG_MUSIC_AUTH            = 0x10003;
    public const BASS_TAG_MUSIC_INST            = 0x10100;
    public const BASS_TAG_MUSIC_CHAN            = 0x10200;
    public const BASS_TAG_MUSIC_SAMPLE          = 0x10300;
    public const BASS_POS_BYTE                  = 0;
    public const BASS_POS_MUSIC_ORDER           = 1;
    public const BASS_POS_OGG                   = 3;
    public const BASS_POS_END                   = 0x10;
    public const BASS_POS_LOOP                  = 0x11;
    public const BASS_POS_FLUSH                 = 0x1000000;
    public const BASS_POS_RESET                 = 0x2000000;
    public const BASS_POS_RELATIVE              = 0x4000000;
    public const BASS_POS_INEXACT               = 0x8000000;
    public const BASS_POS_DECODE                = 0x10000000;
    public const BASS_POS_DECODETO              = 0x20000000;
    public const BASS_POS_SCAN                  = 0x40000000;
    public const BASS_NODEVICE                  = 0x20000;
    public const BASS_INPUT_OFF                 = 0x10000;
    public const BASS_INPUT_ON                  = 0x20000;
    public const BASS_INPUT_TYPE_MASK           = 0xff000000;
    public const BASS_INPUT_TYPE_UNDEF          = 0x00000000;
    public const BASS_INPUT_TYPE_DIGITAL        = 0x01000000;
    public const BASS_INPUT_TYPE_LINE           = 0x02000000;
    public const BASS_INPUT_TYPE_MIC            = 0x03000000;
    public const BASS_INPUT_TYPE_SYNTH          = 0x04000000;
    public const BASS_INPUT_TYPE_CD             = 0x05000000;
    public const BASS_INPUT_TYPE_PHONE          = 0x06000000;
    public const BASS_INPUT_TYPE_SPEAKER        = 0x07000000;
    public const BASS_INPUT_TYPE_WAVE           = 0x08000000;
    public const BASS_INPUT_TYPE_AUX            = 0x09000000;
    public const BASS_INPUT_TYPE_ANALOG         = 0x0a000000;
    public const BASS_FX_DX8_CHORUS             = 0;
    public const BASS_FX_DX8_COMPRESSOR         = 1;
    public const BASS_FX_DX8_DISTORTION         = 2;
    public const BASS_FX_DX8_ECHO               = 3;
    public const BASS_FX_DX8_FLANGER            = 4;
    public const BASS_FX_DX8_GARGLE             = 5;
    public const BASS_FX_DX8_I3DL2REVERB        = 6;
    public const BASS_FX_DX8_PARAMEQ            = 7;
    public const BASS_FX_DX8_REVERB             = 8;
    public const BASS_FX_VOLUME                 = 9;
    public const BASS_DX8_PHASE_NEG_180         = 0;
    public const BASS_DX8_PHASE_NEG_90          = 1;
    public const BASS_DX8_PHASE_ZERO            = 2;
    public const BASS_DX8_PHASE_90              = 3;
    public const BASS_DX8_PHASE_180             = 4;
    public const BASS_IOSNOTIFY_INTERRUPT       = 1;
    public const BASS_IOSNOTIFY_INTERRUPT_END   = 2;

    /**
     * @var non-empty-string
     */
    public readonly string $version;

    /**
     * @var non-empty-string
     */
    public readonly string $binary;

    /**
     * @psalm-taint-sink file $binary
     *
     * @param non-empty-string|null $binary
     * @param non-empty-string|null $version
     * @param Platform|null $platform
     * @param CacheInterface|null $cache
     *
     * @throws DirectiveDefinitionExceptionInterface
     * @throws InvalidArgumentException
     */
    public function __construct(
        ?string $binary = null,
        ?string $version = null,
        public readonly ?Platform $platform = null,
        private readonly ?CacheInterface $cache = null,
    ) {
        assert(Runtime::assertAvailable(), EnvironmentException::getErrorMessageFromStatus());

        $this->binary = $binary ?? $this->detectBinary();
        $this->version = $version ?? $this->detectVersion($this->binary);

        parent::__construct(\FFI::cdef(
            $this->getCachedHeaders($this->platform),
            $this->binary,
        ));
    }

    /**
     * @return non-empty-string
     * @psalm-suppress MoreSpecificReturnType
     * @psalm-suppress LessSpecificReturnStatement
     */
    private function detectBinary(): string
    {
        return match (\PHP_OS_FAMILY) {
            'Windows' => Locator::resolve('bass.dll')
                ?? throw new LoadingException(<<<'error'
                Windows OS requires a reference to BassAL (bass.dll) library.
                Please download the required version of the library from
                the official repository: https://www.un4seen.com/
                error),
            'Linux', 'BSD', 'Solaris' => Locator::resolve('libbass.so')
                ?? throw new LoadingException(<<<'error'
                Could not load BassAL (libbass.so) library.
                Please download the required version of the library from
                the official repository: https://www.un4seen.com/
                error),
            'Darwin' => Locator::resolve('libbass.dylib')
                ?? throw new LoadingException(<<<'error'
                Could not load BassAL (libbass.dylib) library.
                Please download the required version of the library from
                the official repository: https://www.un4seen.com/
                error),
            default => throw new PlatformException(
                'Could not detect library for ' . \PHP_OS
            )
        };
    }

    /**
     * @psalm-taint-sink file $binary
     * @param non-empty-string $binary
     *
     * @return non-empty-string
     */
    private function detectVersion(string $binary): string
    {
        $ffi = \FFI::cdef(<<<'CDATA'
        typedef unsigned long DWORD;
        DWORD BASS_GetVersion(void);
        CDATA, $binary);

        $version = $ffi->BASS_GetVersion();

        return \vsprintf('%d.%d', [
            ($version & 0xff000000) >> 24,
            ($version & 0x00ff0000) >> 16
        ]);
    }

    /**
     * @param Platform|null $platform
     *
     * @return non-empty-string
     *
     * @throws DirectiveDefinitionExceptionInterface
     * @throws InvalidArgumentException
     * @psalm-suppress InvalidThrow
     * @psalm-suppress MoreSpecificReturnType
     * @psalm-suppress LessSpecificReturnStatement
     */
    private function getCachedHeaders(?Platform $platform): string
    {
        $headers = $this->getHeaders($platform);

        if ($this->cache === null) {
            return (string)$headers;
        }

        $key = $this->getCachedHeadersKey($platform);

        try {
            if (!$this->cache->has($key)) {
                $this->cache->set($key, $headers = (string)$headers);

                return $headers;
            }

            return (string)$this->cache->get($key);
        } catch (\Throwable) {
            return (string)$headers;
        }
    }

    /**
     * @param Platform|null $platform
     *
     * @return HeaderInterface
     * @throws DirectiveDefinitionExceptionInterface
     */
    private function getHeaders(?Platform $platform): HeaderInterface
    {
        return BassALHeaders::create(
            platform: match ($platform) {
                Platform::WINDOWS => HeadersPlatform::WINDOWS,
                Platform::LINUX => HeadersPlatform::LINUX,
                Platform::DARWIN => HeadersPlatform::DARWIN,
                default => null,
            },
            version: HeadersVersion::create($this->version),
        );
    }

    /**
     * @param Platform|null $platform
     * @return string
     */
    private function getCachedHeadersKey(?Platform $platform): string
    {
        return \hash('xxh128', \vsprintf('bass-%s|%s', [
            $this->version,
            $platform?->name ?? '<unknown>',
        ]));
    }
}

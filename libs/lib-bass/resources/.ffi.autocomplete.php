<?php

namespace Bic\Lib {

    use Bic\Lib\BassAL\CData\BASS_3DVECTOR;
    use Bic\Lib\BassAL\CData\BASS_CHANNELINFO;
    use Bic\Lib\BassAL\CData\BASS_DEVICEINFO;
    use Bic\Lib\BassAL\CData\BASS_FILEPROCS;
    use Bic\Lib\BassAL\CData\BASS_INFO;
    use Bic\Lib\BassAL\CData\BASS_PLUGININFO;
    use Bic\Lib\BassAL\CData\BASS_RECORDINFO;
    use Bic\Lib\BassAL\CData\BASS_SAMPLE;
    use Bic\Lib\BassAL\CData\HWND;
    use Bic\Lib\BassAL\Platform;
    use FFI\CData;
    use FFI\Proxy\Proxy;
    use Psr\SimpleCache\CacheInterface;

    /**
     * @method int BASS_SetConfig(int $option, int $value)
     * @method int BASS_GetConfig(int $option)
     * @method int BASS_SetConfigPtr(int $option, CData $value)
     * @method CData BASS_GetConfigPtr(int $option)
     * @method int BASS_GetVersion()
     * @method int BASS_ErrorGetCode()
     * @method int BASS_GetDeviceInfo(int $device, BASS_DEVICEINFO|CData|null $info)
     * @method int BASS_Init(int $device, int $freq, int $flags, HWND|CData|null $win, CData|null $dsguid)
     * @method int BASS_Free()
     * @method int BASS_SetDevice(int $device)
     * @method int BASS_GetDevice()
     * @method int BASS_GetInfo(BASS_INFO|CData|null $info)
     * @method int BASS_Start()
     * @method int BASS_Stop()
     * @method int BASS_Pause()
     * @method int BASS_IsStarted()
     * @method int BASS_Update(int $length)
     * @method float BASS_GetCPU()
     * @method int BASS_SetVolume(float $volume)
     * @method float BASS_GetVolume()
     * @method CData BASS_GetDSoundObject(int $object)
     * @method int BASS_Set3DFactors(float $distf, float $rollf, float $doppf)
     * @method int BASS_Get3DFactors(CData|null $distf, CData|null $rollf, CData|null $doppf)
     * @method int BASS_Set3DPosition(BASS_3DVECTOR|CData|null $pos, BASS_3DVECTOR|CData|null $vel, BASS_3DVECTOR|CData|null $front, BASS_3DVECTOR|CData|null $top)
     * @method int BASS_Get3DPosition(BASS_3DVECTOR|CData|null $pos, BASS_3DVECTOR|CData|null $vel, BASS_3DVECTOR|CData|null $front, BASS_3DVECTOR|CData|null $top)
     * @method void BASS_Apply3D()
     * @method int BASS_PluginLoad(string $file, int $flags)
     * @method int BASS_PluginFree(int $handle)
     * @method int BASS_PluginEnable(int $handle, int $enable)
     * @method BASS_PLUGININFO|CData|null BASS_PluginGetInfo(int $handle)
     * @method int BASS_SampleLoad(int $mem, CData|string|null $file, int $offset, int $length, int $max, int $flags)
     * @method int BASS_SampleCreate(int $length, int $freq, int $chans, int $max, int $flags)
     * @method int BASS_SampleFree(int $handle)
     * @method int BASS_SampleSetData(int $handle, CData|null $buffer)
     * @method int BASS_SampleGetData(int $handle, CData|null $buffer)
     * @method int BASS_SampleGetInfo(int $handle, BASS_SAMPLE|CData|null $info)
     * @method int BASS_SampleSetInfo(int $handle, BASS_SAMPLE|CData|null $info)
     * @method int BASS_SampleGetChannel(int $handle, int $flags)
     * @method int BASS_SampleGetChannels(int $handle, CData|null $channels)
     * @method int BASS_SampleStop(int $handle)
     * @method int BASS_StreamCreate(int $freq, int $chans, int $flags, \Closure|CData|int $proc, CData|null $user)
     * @method int BASS_StreamCreateFile(int $mem, CData|string|null $file, int $offset, int $length, int $flags)
     * @method int BASS_StreamCreateURL(string $url, int $offset, int $flags, \Closure $proc, CData|null $user)
     * @method int BASS_StreamCreateFileUser(int $system, int $flags, BASS_FILEPROCS|CData|null $proc, CData|mixed|null $user)
     * @method int BASS_StreamFree(int $handle)
     * @method int BASS_StreamGetFilePosition(int $handle, int $mode)
     * @method int BASS_StreamPutData(int $handle, CData|null $buffer, int $length)
     * @method int BASS_StreamPutFileData(int $handle, CData|null $buffer, int $length)
     * @method int BASS_MusicLoad(int $mem, CData|null $file, int $offset, int $length, int $flags, int $freq)
     * @method int BASS_MusicFree(int $handle)
     * @method int BASS_RecordGetDeviceInfo(int $device, BASS_DEVICEINFO|CData|null $info)
     * @method int BASS_RecordInit(int $device)
     * @method int BASS_RecordFree()
     * @method int BASS_RecordSetDevice(int $device)
     * @method int BASS_RecordGetDevice()
     * @method int BASS_RecordGetInfo(BASS_RECORDINFO|CData|null $info)
     * @method string BASS_RecordGetInputName(int $input)
     * @method int BASS_RecordSetInput(int $input, int $flags, float $volume)
     * @method int BASS_RecordGetInput(int $input, CData|null $volume)
     * @method int BASS_RecordStart(int $freq, int $chans, int $flags, \Closure $proc, CData|null $user)
     * @method float BASS_ChannelBytes2Seconds(int $handle, int $pos)
     * @method int BASS_ChannelSeconds2Bytes(int $handle, float $pos)
     * @method int BASS_ChannelGetDevice(int $handle)
     * @method int BASS_ChannelSetDevice(int $handle, int $device)
     * @method int BASS_ChannelIsActive(int $handle)
     * @method int BASS_ChannelGetInfo(int $handle, BASS_CHANNELINFO|CData|null $info)
     * @method string BASS_ChannelGetTags(int $handle, int $tags)
     * @method int BASS_ChannelFlags(int $handle, int $flags, int $mask)
     * @method int BASS_ChannelLock(int $handle, int $lock)
     * @method int BASS_ChannelFree(int $handle)
     * @method int BASS_ChannelPlay(int $handle, int $restart)
     * @method int BASS_ChannelStop(int $handle)
     * @method int BASS_ChannelPause(int $handle)
     * @method int BASS_ChannelUpdate(int $handle, int $length)
     * @method int BASS_ChannelSetAttribute(int $handle, int $attrib, float $value)
     * @method int BASS_ChannelGetAttribute(int $handle, int $attrib, CData|null $value)
     * @method int BASS_ChannelSlideAttribute(int $handle, int $attrib, float $value, int $time)
     * @method int BASS_ChannelIsSliding(int $handle, int $attrib)
     * @method int BASS_ChannelSetAttributeEx(int $handle, int $attrib, CData|null $value, int $size)
     * @method int BASS_ChannelGetAttributeEx(int $handle, int $attrib, CData|null $value, int $size)
     * @method int BASS_ChannelSet3DAttributes(int $handle, int $mode, float $min, float $max, int $iangle, int $oangle, float $outvol)
     * @method int BASS_ChannelGet3DAttributes(int $handle, CData|null $mode, CData|null $min, CData|null $max, CData|null $iangle, CData|null $oangle, CData|null $outvol)
     * @method int BASS_ChannelSet3DPosition(int $handle, BASS_3DVECTOR|CData|null $pos, BASS_3DVECTOR|CData|null $orient, BASS_3DVECTOR|CData|null $vel)
     * @method int BASS_ChannelGet3DPosition(int $handle, BASS_3DVECTOR|CData|null $pos, BASS_3DVECTOR|CData|null $orient, BASS_3DVECTOR|CData|null $vel)
     * @method int BASS_ChannelGetLength(int $handle, int $mode)
     * @method int BASS_ChannelSetPosition(int $handle, int $pos, int $mode)
     * @method int BASS_ChannelGetPosition(int $handle, int $mode)
     * @method int BASS_ChannelGetLevel(int $handle)
     * @method int BASS_ChannelGetLevelEx(int $handle, CData|null $levels, float $length, int $flags)
     * @method int BASS_ChannelGetData(int $handle, CData|null $buffer, int $length)
     * @method int BASS_ChannelSetSync(int $handle, int $type, int $param, \Closure $proc, CData|null $user)
     * @method int BASS_ChannelRemoveSync(int $handle, int $sync)
     * @method int BASS_ChannelSetLink(int $handle, int $chan)
     * @method int BASS_ChannelRemoveLink(int $handle, int $chan)
     * @method int BASS_ChannelSetDSP(int $handle, \Closure $proc, CData|null $user, int $priority)
     * @method int BASS_ChannelRemoveDSP(int $handle, int $dsp)
     * @method int BASS_ChannelSetFX(int $handle, int $type, int $priority)
     * @method int BASS_ChannelRemoveFX(int $handle, int $fx)
     * @method int BASS_FXSetParameters(int $handle, CData|null $params)
     * @method int BASS_FXGetParameters(int $handle, CData|null $params)
     * @method int BASS_FXSetPriority(int $handle, int $priority)
     * @method int BASS_FXReset(int $handle)
     */
    final class BassAL extends Proxy
    {
        public function __construct(
            ?string $binary = null,
            ?string $version = null,
            public readonly ?Platform $platform = null,
            private readonly ?CacheInterface $cache = null,
        ) {
        }
    }
}

namespace Bic\Lib\BassAL {

    use Bic\Lib\BassAL\CData\BASS_MIXER_NODE;
    use FFI\CData;
    use FFI\Proxy\Proxy;
    use Psr\SimpleCache\CacheInterface;


    /**
     * @method int BASS_Mixer_GetVersion()
     * @method int BASS_Mixer_StreamCreate(int $freq, int $chans, int $flags)
     * @method int BASS_Mixer_StreamAddChannel(int $handle, int $channel, int $flags)
     * @method int BASS_Mixer_StreamAddChannelEx(int $handle, int $channel, int $flags, int $start, int $length)
     * @method int BASS_Mixer_StreamGetChannels(int $handle, CData $channels, int $count)
     * @method int BASS_Mixer_ChannelGetMixer(int $handle)
     * @method int BASS_Mixer_ChannelIsActive(int $handle)
     * @method int BASS_Mixer_ChannelFlags(int $handle, int $flags, int $mask)
     * @method int BASS_Mixer_ChannelRemove(int $handle)
     * @method int BASS_Mixer_ChannelSetPosition(int $handle, int $pos, int $mode)
     * @method int BASS_Mixer_ChannelGetPosition(int $handle, int $mode)
     * @method int BASS_Mixer_ChannelGetPositionEx(int $channel, int $mode, int $delay)
     * @method int BASS_Mixer_ChannelGetLevel(int $handle)
     * @method int BASS_Mixer_ChannelGetLevelEx(int $handle, CData $levels, float $length, int $flags)
     * @method int BASS_Mixer_ChannelGetData(int $handle, CData $buffer, int $length)
     * @method int BASS_Mixer_ChannelSetSync(int $handle, int $type, int $param, \Closure|CData|null $proc, CData|null $user)
     * @method int BASS_Mixer_ChannelRemoveSync(int $channel, int $sync)
     * @method int BASS_Mixer_ChannelSetMatrix(int $handle, CData $matrix)
     * @method int BASS_Mixer_ChannelSetMatrixEx(int $handle, CData $matrix, float $time)
     * @method int BASS_Mixer_ChannelGetMatrix(int $handle, CData $matrix)
     * @method int BASS_Mixer_ChannelSetEnvelope(int $handle, int $type, CData|BASS_MIXER_NODE $nodes, int $count)
     * @method int BASS_Mixer_ChannelSetEnvelopePos(int $handle, int $type, int $pos)
     * @method int BASS_Mixer_ChannelGetEnvelopePos(int $handle, int $type, CData $value)
     * @method int BASS_Split_StreamCreate(int $channel, int $flags, CData $chanmap)
     * @method int BASS_Split_StreamGetSource(int $handle)
     * @method int BASS_Split_StreamGetSplits(int $handle, CData $splits, int $count)
     * @method int BASS_Split_StreamReset(int $handle)
     * @method int BASS_Split_StreamResetEx(int $handle, int $offset)
     * @method int BASS_Split_StreamGetAvailable(int $handle)
     */
    final class Mixer extends Proxy
    {
        public function __construct(
            ?string $binary = null,
            ?string $version = null,
            public readonly ?Platform $platform = null,
            private readonly ?CacheInterface $cache = null,
        ) {
        }
    }
}

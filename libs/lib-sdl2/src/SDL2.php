<?php

/**
 * This file is part of Bic Engine package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Bic\Lib;

use Bic\Lib\SDL2\Exception\LoadingException;
use Bic\Lib\SDL2\Exception\PlatformException;
use Bic\Lib\SDL2\Platform;
use FFI\Contracts\Headers\HeaderInterface;
use FFI\Contracts\Preprocessor\Exception\DirectiveDefinitionExceptionInterface;
use FFI\Env\Exception\EnvironmentException;
use FFI\Env\Runtime;
use FFI\Headers\SDL2 as SDL2Headers;
use FFI\Headers\SDL2\Platform as HeadersPlatform;
use FFI\Headers\SDL2\Version as HeadersVersion;
use FFI\Location\Locator;
use FFI\Proxy\Proxy;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;

final class SDL2 extends Proxy
{
    public const SDL_FALSE                                         = 0;
    public const SDL_TRUE                                          = 1;
    public const SDL_INIT_TIMER                                    = 0x00000001;
    public const SDL_INIT_AUDIO                                    = 0x00000010;
    public const SDL_INIT_VIDEO                                    = 0x00000020;
    public const SDL_INIT_JOYSTICK                                 = 0x00000200;
    public const SDL_INIT_HAPTIC                                   = 0x00001000;
    public const SDL_INIT_GAMECONTROLLER                           = 0x00002000;
    public const SDL_INIT_EVENTS                                   = 0x00004000;
    public const SDL_INIT_SENSOR                                   = 0x00008000;
    public const SDL_INIT_NOPARACHUTE                              = 0x00100000;
    public const SDL_INIT_EVERYTHING                               = self::SDL_INIT_TIMER
                                                                   | self::SDL_INIT_AUDIO
                                                                   | self::SDL_INIT_VIDEO
                                                                   | self::SDL_INIT_EVENTS
                                                                   | self::SDL_INIT_JOYSTICK
                                                                   | self::SDL_INIT_HAPTIC
                                                                   | self::SDL_INIT_GAMECONTROLLER
                                                                   | self::SDL_INIT_SENSOR;
    public const SDL_AUDIO_MASK_BITSIZE                            = 0xFF;
    public const SDL_AUDIO_MASK_DATATYPE                           = 1 << 8;
    public const SDL_AUDIO_MASK_ENDIAN                             = 1 << 12;
    public const SDL_AUDIO_MASK_SIGNED                             = 1 << 15;
    public const SDL_AUDIO_ALLOW_FREQUENCY_CHANGE                  = 0x00000001;
    public const SDL_AUDIO_ALLOW_FORMAT_CHANGE                     = 0x00000002;
    public const SDL_AUDIO_ALLOW_CHANNELS_CHANGE                   = 0x00000004;
    public const SDL_AUDIO_ALLOW_SAMPLES_CHANGE                    = 0x00000008;
    public const SDL_AUDIO_ALLOW_ANY_CHANGE                        = self::SDL_AUDIO_ALLOW_FREQUENCY_CHANGE
                                                                   | self::SDL_AUDIO_ALLOW_FORMAT_CHANGE
                                                                   | self::SDL_AUDIO_ALLOW_CHANNELS_CHANGE
                                                                   | self::SDL_AUDIO_ALLOW_SAMPLES_CHANGE;
    public const SDL_AUDIOCVT_MAX_FILTERS                          = 9;
    public const SDL_MIX_MAXVOLUME                                 = 128;
    public const SDL_CACHELINE_SIZE                                = 128;
    public const SDL_LIL_ENDIAN                                    = 1234;
    public const SDL_BIG_ENDIAN                                    = 4321;
    public const SDL_RELEASED                                      = 0;
    public const SDL_PRESSED                                       = 1;
    public const SDL_QUERY                                         = -1;
    public const SDL_IGNORE                                        = 0;
    public const SDL_DISABLE                                       = 0;
    public const SDL_ENABLE                                        = 1;
    public const SDL_HAPTIC_CONSTANT                               = 1 << 0;
    public const SDL_HAPTIC_SINE                                   = 1 << 1;
    public const SDL_HAPTIC_LEFTRIGHT                              = 1 << 2;
    public const SDL_HAPTIC_TRIANGLE                               = 1 << 3;
    public const SDL_HAPTIC_SAWTOOTHUP                             = 1 << 4;
    public const SDL_HAPTIC_SAWTOOTHDOWN                           = 1 << 5;
    public const SDL_HAPTIC_RAMP                                   = 1 << 6;
    public const SDL_HAPTIC_SPRING                                 = 1 << 7;
    public const SDL_HAPTIC_DAMPER                                 = 1 << 8;
    public const SDL_HAPTIC_INERTIA                                = 1 << 9;
    public const SDL_HAPTIC_FRICTION                               = 1 << 10;
    public const SDL_HAPTIC_CUSTOM                                 = 1 << 11;
    public const SDL_HAPTIC_GAIN                                   = 1 << 12;
    public const SDL_HAPTIC_AUTOCENTER                             = 1 << 13;
    public const SDL_HAPTIC_STATUS                                 = 1 << 14;
    public const SDL_HAPTIC_PAUSE                                  = 1 << 15;
    public const SDL_HAPTIC_POLAR                                  = 0;
    public const SDL_HAPTIC_CARTESIAN                              = 1;
    public const SDL_HAPTIC_SPHERICAL                              = 2;
    public const SDL_HAPTIC_STEERING_AXIS                          = 3;
    public const SDL_HAPTIC_INFINITY                               = 4294967295;
    public const SDL_HINT_ACCELEROMETER_AS_JOYSTICK                = 'SDL_ACCELEROMETER_AS_JOYSTICK';
    public const SDL_HINT_ALLOW_ALT_TAB_WHILE_GRABBED              = 'SDL_ALLOW_ALT_TAB_WHILE_GRABBED';
    public const SDL_HINT_ALLOW_TOPMOST                            = 'SDL_ALLOW_TOPMOST';
    public const SDL_HINT_ANDROID_APK_EXPANSION_MAIN_FILE_VERSION  = 'SDL_ANDROID_APK_EXPANSION_MAIN_FILE_VERSION';
    public const SDL_HINT_ANDROID_APK_EXPANSION_PATCH_FILE_VERSION = 'SDL_ANDROID_APK_EXPANSION_PATCH_FILE_VERSION';
    public const SDL_HINT_ANDROID_BLOCK_ON_PAUSE                   = 'SDL_ANDROID_BLOCK_ON_PAUSE';
    public const SDL_HINT_ANDROID_BLOCK_ON_PAUSE_PAUSEAUDIO        = 'SDL_ANDROID_BLOCK_ON_PAUSE_PAUSEAUDIO';
    public const SDL_HINT_ANDROID_TRAP_BACK_BUTTON                 = 'SDL_ANDROID_TRAP_BACK_BUTTON';
    public const SDL_HINT_APP_NAME                                 = 'SDL_APP_NAME';
    public const SDL_HINT_APPLE_TV_CONTROLLER_UI_EVENTS            = 'SDL_APPLE_TV_CONTROLLER_UI_EVENTS';
    public const SDL_HINT_APPLE_TV_REMOTE_ALLOW_ROTATION           = 'SDL_APPLE_TV_REMOTE_ALLOW_ROTATION';
    public const SDL_HINT_AUDIO_CATEGORY                           = 'SDL_AUDIO_CATEGORY';
    public const SDL_HINT_AUDIO_DEVICE_APP_NAME                    = 'SDL_AUDIO_DEVICE_APP_NAME';
    public const SDL_HINT_AUDIO_DEVICE_STREAM_NAME                 = 'SDL_AUDIO_DEVICE_STREAM_NAME';
    public const SDL_HINT_AUDIO_DEVICE_STREAM_ROLE                 = 'SDL_AUDIO_DEVICE_STREAM_ROLE';
    public const SDL_HINT_AUDIO_RESAMPLING_MODE                    = 'SDL_AUDIO_RESAMPLING_MODE';
    public const SDL_HINT_AUTO_UPDATE_JOYSTICKS                    = 'SDL_AUTO_UPDATE_JOYSTICKS';
    public const SDL_HINT_AUTO_UPDATE_SENSORS                      = 'SDL_AUTO_UPDATE_SENSORS';
    public const SDL_HINT_BMP_SAVE_LEGACY_FORMAT                   = 'SDL_BMP_SAVE_LEGACY_FORMAT';
    public const SDL_HINT_DISPLAY_USABLE_BOUNDS                    = 'SDL_DISPLAY_USABLE_BOUNDS';
    public const SDL_HINT_EMSCRIPTEN_ASYNCIFY                      = 'SDL_EMSCRIPTEN_ASYNCIFY';
    public const SDL_HINT_EMSCRIPTEN_KEYBOARD_ELEMENT              = 'SDL_EMSCRIPTEN_KEYBOARD_ELEMENT';
    public const SDL_HINT_ENABLE_STEAM_CONTROLLERS                 = 'SDL_ENABLE_STEAM_CONTROLLERS';
    public const SDL_HINT_EVENT_LOGGING                            = 'SDL_EVENT_LOGGING';
    public const SDL_HINT_FORCE_RAISEWINDOW                        = 'SDL_HINT_FORCE_RAISEWINDOW';
    public const SDL_HINT_FRAMEBUFFER_ACCELERATION                 = 'SDL_FRAMEBUFFER_ACCELERATION';
    public const SDL_HINT_GAMECONTROLLERCONFIG                     = 'SDL_GAMECONTROLLERCONFIG';
    public const SDL_HINT_GAMECONTROLLERCONFIG_FILE                = 'SDL_GAMECONTROLLERCONFIG_FILE';
    public const SDL_HINT_GAMECONTROLLERTYPE                       = 'SDL_GAMECONTROLLERTYPE';
    public const SDL_HINT_GAMECONTROLLER_IGNORE_DEVICES            = 'SDL_GAMECONTROLLER_IGNORE_DEVICES';
    public const SDL_HINT_GAMECONTROLLER_IGNORE_DEVICES_EXCEPT     = 'SDL_GAMECONTROLLER_IGNORE_DEVICES_EXCEPT';
    public const SDL_HINT_GAMECONTROLLER_USE_BUTTON_LABELS         = 'SDL_GAMECONTROLLER_USE_BUTTON_LABELS';
    public const SDL_HINT_GRAB_KEYBOARD                            = 'SDL_GRAB_KEYBOARD';
    public const SDL_HINT_IDLE_TIMER_DISABLED                      = 'SDL_IOS_IDLE_TIMER_DISABLED';
    public const SDL_HINT_IME_INTERNAL_EDITING                     = 'SDL_IME_INTERNAL_EDITING';
    public const SDL_HINT_IME_SHOW_UI                              = 'SDL_IME_SHOW_UI';
    public const SDL_HINT_IME_SUPPORT_EXTENDED_TEXT                = 'SDL_IME_SUPPORT_EXTENDED_TEXT';
    public const SDL_HINT_IOS_HIDE_HOME_INDICATOR                  = 'SDL_IOS_HIDE_HOME_INDICATOR';
    public const SDL_HINT_JOYSTICK_ALLOW_BACKGROUND_EVENTS         = 'SDL_JOYSTICK_ALLOW_BACKGROUND_EVENTS';
    public const SDL_HINT_JOYSTICK_HIDAPI                          = 'SDL_JOYSTICK_HIDAPI';
    public const SDL_HINT_JOYSTICK_HIDAPI_GAMECUBE                 = 'SDL_JOYSTICK_HIDAPI_GAMECUBE';
    public const SDL_HINT_JOYSTICK_GAMECUBE_RUMBLE_BRAKE           = 'SDL_JOYSTICK_GAMECUBE_RUMBLE_BRAKE';
    public const SDL_HINT_JOYSTICK_HIDAPI_JOY_CONS                 = 'SDL_JOYSTICK_HIDAPI_JOY_CONS';
    public const SDL_HINT_JOYSTICK_HIDAPI_COMBINE_JOY_CONS         = 'SDL_JOYSTICK_HIDAPI_COMBINE_JOY_CONS';
    public const SDL_HINT_JOYSTICK_HIDAPI_LUNA                     = 'SDL_JOYSTICK_HIDAPI_LUNA';
    public const SDL_HINT_JOYSTICK_HIDAPI_NINTENDO_CLASSIC         = 'SDL_JOYSTICK_HIDAPI_NINTENDO_CLASSIC';
    public const SDL_HINT_JOYSTICK_HIDAPI_SHIELD                   = 'SDL_JOYSTICK_HIDAPI_SHIELD';
    public const SDL_HINT_JOYSTICK_HIDAPI_PS4                      = 'SDL_JOYSTICK_HIDAPI_PS4';
    public const SDL_HINT_JOYSTICK_HIDAPI_PS4_RUMBLE               = 'SDL_JOYSTICK_HIDAPI_PS4_RUMBLE';
    public const SDL_HINT_JOYSTICK_HIDAPI_PS5                      = 'SDL_JOYSTICK_HIDAPI_PS5';
    public const SDL_HINT_JOYSTICK_HIDAPI_PS5_PLAYER_LED           = 'SDL_JOYSTICK_HIDAPI_PS5_PLAYER_LED';
    public const SDL_HINT_JOYSTICK_HIDAPI_PS5_RUMBLE               = 'SDL_JOYSTICK_HIDAPI_PS5_RUMBLE';
    public const SDL_HINT_JOYSTICK_HIDAPI_STADIA                   = 'SDL_JOYSTICK_HIDAPI_STADIA';
    public const SDL_HINT_JOYSTICK_HIDAPI_STEAM                    = 'SDL_JOYSTICK_HIDAPI_STEAM';
    public const SDL_HINT_JOYSTICK_HIDAPI_SWITCH                   = 'SDL_JOYSTICK_HIDAPI_SWITCH';
    public const SDL_HINT_JOYSTICK_HIDAPI_SWITCH_HOME_LED          = 'SDL_JOYSTICK_HIDAPI_SWITCH_HOME_LED';
    public const SDL_HINT_JOYSTICK_HIDAPI_JOYCON_HOME_LED          = 'SDL_JOYSTICK_HIDAPI_JOYCON_HOME_LED';
    public const SDL_HINT_JOYSTICK_HIDAPI_SWITCH_PLAYER_LED        = 'SDL_JOYSTICK_HIDAPI_SWITCH_PLAYER_LED';
    public const SDL_HINT_JOYSTICK_HIDAPI_XBOX                     = 'SDL_JOYSTICK_HIDAPI_XBOX';
    public const SDL_HINT_JOYSTICK_RAWINPUT                        = 'SDL_JOYSTICK_RAWINPUT';
    public const SDL_HINT_JOYSTICK_RAWINPUT_CORRELATE_XINPUT       = 'SDL_JOYSTICK_RAWINPUT_CORRELATE_XINPUT';
    public const SDL_HINT_JOYSTICK_ROG_CHAKRAM                     = 'SDL_JOYSTICK_ROG_CHAKRAM';
    public const SDL_HINT_JOYSTICK_THREAD                          = 'SDL_JOYSTICK_THREAD';
    public const SDL_HINT_KMSDRM_REQUIRE_DRM_MASTER                = 'SDL_KMSDRM_REQUIRE_DRM_MASTER';
    public const SDL_HINT_JOYSTICK_DEVICE                          = 'SDL_JOYSTICK_DEVICE';
    public const SDL_HINT_LINUX_DIGITAL_HATS                       = 'SDL_LINUX_DIGITAL_HATS';
    public const SDL_HINT_LINUX_HAT_DEADZONES                      = 'SDL_LINUX_HAT_DEADZONES';
    public const SDL_HINT_LINUX_JOYSTICK_CLASSIC                   = 'SDL_LINUX_JOYSTICK_CLASSIC';
    public const SDL_HINT_LINUX_JOYSTICK_DEADZONES                 = 'SDL_LINUX_JOYSTICK_DEADZONES';
    public const SDL_HINT_MAC_BACKGROUND_APP                       = 'SDL_MAC_BACKGROUND_APP';
    public const SDL_HINT_MAC_CTRL_CLICK_EMULATE_RIGHT_CLICK       = 'SDL_MAC_CTRL_CLICK_EMULATE_RIGHT_CLICK';
    public const SDL_HINT_MAC_OPENGL_ASYNC_DISPATCH                = 'SDL_MAC_OPENGL_ASYNC_DISPATCH';
    public const SDL_HINT_MOUSE_DOUBLE_CLICK_RADIUS                = 'SDL_MOUSE_DOUBLE_CLICK_RADIUS';
    public const SDL_HINT_MOUSE_DOUBLE_CLICK_TIME                  = 'SDL_MOUSE_DOUBLE_CLICK_TIME';
    public const SDL_HINT_MOUSE_FOCUS_CLICKTHROUGH                 = 'SDL_MOUSE_FOCUS_CLICKTHROUGH';
    public const SDL_HINT_MOUSE_NORMAL_SPEED_SCALE                 = 'SDL_MOUSE_NORMAL_SPEED_SCALE';
    public const SDL_HINT_MOUSE_RELATIVE_MODE_CENTER               = 'SDL_MOUSE_RELATIVE_MODE_CENTER';
    public const SDL_HINT_MOUSE_RELATIVE_MODE_WARP                 = 'SDL_MOUSE_RELATIVE_MODE_WARP';
    public const SDL_HINT_MOUSE_RELATIVE_SCALING                   = 'SDL_MOUSE_RELATIVE_SCALING';
    public const SDL_HINT_MOUSE_RELATIVE_SPEED_SCALE               = 'SDL_MOUSE_RELATIVE_SPEED_SCALE';
    public const SDL_HINT_MOUSE_RELATIVE_WARP_MOTION               = 'SDL_MOUSE_RELATIVE_WARP_MOTION';
    public const SDL_HINT_MOUSE_TOUCH_EVENTS                       = 'SDL_MOUSE_TOUCH_EVENTS';
    public const SDL_HINT_MOUSE_AUTO_CAPTURE                       = 'SDL_MOUSE_AUTO_CAPTURE';
    public const SDL_HINT_NO_SIGNAL_HANDLERS                       = 'SDL_NO_SIGNAL_HANDLERS';
    public const SDL_HINT_OPENGL_ES_DRIVER                         = 'SDL_OPENGL_ES_DRIVER';
    public const SDL_HINT_ORIENTATIONS                             = 'SDL_IOS_ORIENTATIONS';
    public const SDL_HINT_POLL_SENTINEL                            = 'SDL_POLL_SENTINEL';
    public const SDL_HINT_PREFERRED_LOCALES                        = 'SDL_PREFERRED_LOCALES';
    public const SDL_HINT_QTWAYLAND_CONTENT_ORIENTATION            = 'SDL_QTWAYLAND_CONTENT_ORIENTATION';
    public const SDL_HINT_QTWAYLAND_WINDOW_FLAGS                   = 'SDL_QTWAYLAND_WINDOW_FLAGS';
    public const SDL_HINT_RENDER_BATCHING                          = 'SDL_RENDER_BATCHING';
    public const SDL_HINT_RENDER_LINE_METHOD                       = 'SDL_RENDER_LINE_METHOD';
    public const SDL_HINT_RENDER_DIRECT3D11_DEBUG                  = 'SDL_RENDER_DIRECT3D11_DEBUG';
    public const SDL_HINT_RENDER_DIRECT3D_THREADSAFE               = 'SDL_RENDER_DIRECT3D_THREADSAFE';
    public const SDL_HINT_RENDER_DRIVER                            = 'SDL_RENDER_DRIVER';
    public const SDL_HINT_RENDER_LOGICAL_SIZE_MODE                 = 'SDL_RENDER_LOGICAL_SIZE_MODE';
    public const SDL_HINT_RENDER_OPENGL_SHADERS                    = 'SDL_RENDER_OPENGL_SHADERS';
    public const SDL_HINT_RENDER_SCALE_QUALITY                     = 'SDL_RENDER_SCALE_QUALITY';
    public const SDL_HINT_RENDER_VSYNC                             = 'SDL_RENDER_VSYNC';
    public const SDL_HINT_RETURN_KEY_HIDES_IME                     = 'SDL_RETURN_KEY_HIDES_IME';
    public const SDL_HINT_RPI_VIDEO_LAYER                          = 'SDL_RPI_VIDEO_LAYER';
    public const SDL_HINT_SCREENSAVER_INHIBIT_ACTIVITY_NAME        = 'SDL_SCREENSAVER_INHIBIT_ACTIVITY_NAME';
    public const SDL_HINT_THREAD_FORCE_REALTIME_TIME_CRITICAL      = 'SDL_THREAD_FORCE_REALTIME_TIME_CRITICAL';
    public const SDL_HINT_THREAD_PRIORITY_POLICY                   = 'SDL_THREAD_PRIORITY_POLICY';
    public const SDL_HINT_THREAD_STACK_SIZE                        = 'SDL_THREAD_STACK_SIZE';
    public const SDL_HINT_TIMER_RESOLUTION                         = 'SDL_TIMER_RESOLUTION';
    public const SDL_HINT_TOUCH_MOUSE_EVENTS                       = 'SDL_TOUCH_MOUSE_EVENTS';
    public const SDL_HINT_VITA_TOUCH_MOUSE_DEVICE                  = 'SDL_HINT_VITA_TOUCH_MOUSE_DEVICE';
    public const SDL_HINT_TV_REMOTE_AS_JOYSTICK                    = 'SDL_TV_REMOTE_AS_JOYSTICK';
    public const SDL_HINT_VIDEO_ALLOW_SCREENSAVER                  = 'SDL_VIDEO_ALLOW_SCREENSAVER';
    public const SDL_HINT_VIDEO_DOUBLE_BUFFER                      = 'SDL_VIDEO_DOUBLE_BUFFER';
    public const SDL_HINT_VIDEO_EGL_ALLOW_TRANSPARENCY             = 'SDL_VIDEO_EGL_ALLOW_TRANSPARENCY';
    public const SDL_HINT_VIDEO_EXTERNAL_CONTEXT                   = 'SDL_VIDEO_EXTERNAL_CONTEXT';
    public const SDL_HINT_VIDEO_HIGHDPI_DISABLED                   = 'SDL_VIDEO_HIGHDPI_DISABLED';
    public const SDL_HINT_VIDEO_MAC_FULLSCREEN_SPACES              = 'SDL_VIDEO_MAC_FULLSCREEN_SPACES';
    public const SDL_HINT_VIDEO_MINIMIZE_ON_FOCUS_LOSS             = 'SDL_VIDEO_MINIMIZE_ON_FOCUS_LOSS';
    public const SDL_HINT_VIDEO_WAYLAND_ALLOW_LIBDECOR             = 'SDL_VIDEO_WAYLAND_ALLOW_LIBDECOR';
    public const SDL_HINT_VIDEO_WAYLAND_PREFER_LIBDECOR            = 'SDL_VIDEO_WAYLAND_PREFER_LIBDECOR';
    public const SDL_HINT_VIDEO_WAYLAND_MODE_EMULATION             = 'SDL_VIDEO_WAYLAND_MODE_EMULATION';
    public const SDL_HINT_VIDEO_WINDOW_SHARE_PIXEL_FORMAT          = 'SDL_VIDEO_WINDOW_SHARE_PIXEL_FORMAT';
    public const SDL_HINT_VIDEO_FOREIGN_WINDOW_OPENGL              = 'SDL_VIDEO_FOREIGN_WINDOW_OPENGL';
    public const SDL_HINT_VIDEO_FOREIGN_WINDOW_VULKAN              = 'SDL_VIDEO_FOREIGN_WINDOW_VULKAN';
    public const SDL_HINT_VIDEO_WIN_D3DCOMPILER                    = 'SDL_VIDEO_WIN_D3DCOMPILER';
    public const SDL_HINT_VIDEO_X11_FORCE_EGL                      = 'SDL_VIDEO_X11_FORCE_EGL';
    public const SDL_HINT_VIDEO_X11_NET_WM_BYPASS_COMPOSITOR       = 'SDL_VIDEO_X11_NET_WM_BYPASS_COMPOSITOR';
    public const SDL_HINT_VIDEO_X11_NET_WM_PING                    = 'SDL_VIDEO_X11_NET_WM_PING';
    public const SDL_HINT_VIDEO_X11_WINDOW_VISUALID                = 'SDL_VIDEO_X11_WINDOW_VISUALID';
    public const SDL_HINT_VIDEO_X11_XINERAMA                       = 'SDL_VIDEO_X11_XINERAMA';
    public const SDL_HINT_VIDEO_X11_XRANDR                         = 'SDL_VIDEO_X11_XRANDR';
    public const SDL_HINT_VIDEO_X11_XVIDMODE                       = 'SDL_VIDEO_X11_XVIDMODE';
    public const SDL_HINT_WAVE_FACT_CHUNK                          = 'SDL_WAVE_FACT_CHUNK';
    public const SDL_HINT_WAVE_RIFF_CHUNK_SIZE                     = 'SDL_WAVE_RIFF_CHUNK_SIZE';
    public const SDL_HINT_WAVE_TRUNCATION                          = 'SDL_WAVE_TRUNCATION';
    public const SDL_HINT_WINDOWS_DISABLE_THREAD_NAMING            = 'SDL_WINDOWS_DISABLE_THREAD_NAMING';
    public const SDL_HINT_WINDOWS_ENABLE_MESSAGELOOP               = 'SDL_WINDOWS_ENABLE_MESSAGELOOP';
    public const SDL_HINT_WINDOWS_FORCE_MUTEX_CRITICAL_SECTIONS    = 'SDL_WINDOWS_FORCE_MUTEX_CRITICAL_SECTIONS';
    public const SDL_HINT_WINDOWS_FORCE_SEMAPHORE_KERNEL           = 'SDL_WINDOWS_FORCE_SEMAPHORE_KERNEL';
    public const SDL_HINT_WINDOWS_INTRESOURCE_ICON                 = 'SDL_WINDOWS_INTRESOURCE_ICON';
    public const SDL_HINT_WINDOWS_INTRESOURCE_ICON_SMALL           = 'SDL_WINDOWS_INTRESOURCE_ICON_SMALL';
    public const SDL_HINT_WINDOWS_NO_CLOSE_ON_ALT_F4               = 'SDL_WINDOWS_NO_CLOSE_ON_ALT_F4';
    public const SDL_HINT_WINDOWS_USE_D3D9EX                       = 'SDL_WINDOWS_USE_D3D9EX';
    public const SDL_HINT_WINDOWS_DPI_AWARENESS                    = 'SDL_WINDOWS_DPI_AWARENESS';
    public const SDL_HINT_WINDOWS_DPI_SCALING                      = 'SDL_WINDOWS_DPI_SCALING';
    public const SDL_HINT_WINDOW_FRAME_USABLE_WHILE_CURSOR_HIDDEN  = 'SDL_WINDOW_FRAME_USABLE_WHILE_CURSOR_HIDDEN';
    public const SDL_HINT_WINDOW_NO_ACTIVATION_WHEN_SHOWN          = 'SDL_WINDOW_NO_ACTIVATION_WHEN_SHOWN';
    public const SDL_HINT_WINRT_HANDLE_BACK_BUTTON                 = 'SDL_WINRT_HANDLE_BACK_BUTTON';
    public const SDL_HINT_WINRT_PRIVACY_POLICY_LABEL               = 'SDL_WINRT_PRIVACY_POLICY_LABEL';
    public const SDL_HINT_WINRT_PRIVACY_POLICY_URL                 = 'SDL_WINRT_PRIVACY_POLICY_URL';
    public const SDL_HINT_X11_FORCE_OVERRIDE_REDIRECT              = 'SDL_X11_FORCE_OVERRIDE_REDIRECT';
    public const SDL_HINT_XINPUT_ENABLED                           = 'SDL_XINPUT_ENABLED';
    public const SDL_HINT_DIRECTINPUT_ENABLED                      = 'SDL_DIRECTINPUT_ENABLED';
    public const SDL_HINT_XINPUT_USE_OLD_JOYSTICK_MAPPING          = 'SDL_XINPUT_USE_OLD_JOYSTICK_MAPPING';
    public const SDL_HINT_AUDIO_INCLUDE_MONITORS                   = 'SDL_AUDIO_INCLUDE_MONITORS';
    public const SDL_HINT_X11_WINDOW_TYPE                          = 'SDL_X11_WINDOW_TYPE';
    public const SDL_HINT_QUIT_ON_LAST_WINDOW_CLOSE                = 'SDL_QUIT_ON_LAST_WINDOW_CLOSE';
    public const SDL_HINT_VIDEODRIVER                              = 'SDL_VIDEODRIVER';
    public const SDL_HINT_AUDIODRIVER                              = 'SDL_AUDIODRIVER';
    public const SDL_HINT_KMSDRM_DEVICE_INDEX                      = 'SDL_KMSDRM_DEVICE_INDEX';
    public const SDL_HINT_TRACKPAD_IS_TOUCH_ONLY                   = 'SDL_TRACKPAD_IS_TOUCH_ONLY';
    public const SDL_IPHONE_MAX_GFORCE                             = 5.0;
    public const SDL_VIRTUAL_JOYSTICK_DESC_VERSION                 = 1;
    public const SDL_JOYSTICK_AXIS_MAX                             = 32767;
    public const SDL_JOYSTICK_AXIS_MIN                             = -32768;
    public const SDL_HAT_CENTERED                                  = 0x00;
    public const SDL_HAT_UP                                        = 0x01;
    public const SDL_HAT_RIGHT                                     = 0x02;
    public const SDL_HAT_DOWN                                      = 0x04;
    public const SDL_HAT_LEFT                                      = 0x08;
    public const SDL_HAT_RIGHTUP                                   = self::SDL_HAT_RIGHT
                                                                   | self::SDL_HAT_UP;
    public const SDL_HAT_RIGHTDOWN                                 = self::SDL_HAT_RIGHT
                                                                   | self::SDL_HAT_DOWN;
    public const SDL_HAT_LEFTUP                                    = self::SDL_HAT_LEFT
                                                                   | self::SDL_HAT_UP;
    public const SDL_HAT_LEFTDOWN                                  = self::SDL_HAT_LEFT
                                                                   | self::SDL_HAT_DOWN;
    public const SDLK_SCANCODE_MASK                                = 1 << 30;
    public const SDL_MAX_LOG_MESSAGE                               = 4096;
    public const SDL_BUTTON_LEFT                                   = 1;
    public const SDL_BUTTON_MIDDLE                                 = 2;
    public const SDL_BUTTON_RIGHT                                  = 3;
    public const SDL_BUTTON_X1                                     = 4;
    public const SDL_BUTTON_X2                                     = 5;
    public const SDL_MUTEX_MAXWAIT                                 = ~0;
    public const SDL_ALPHA_OPAQUE                                  = 255;
    public const SDL_ALPHA_TRANSPARENT                             = 0;
    public const SDL_RWOPS_UNKNOWN                                 = 0;
    public const SDL_RWOPS_WINFILE                                 = 1;
    public const SDL_RWOPS_STDFILE                                 = 2;
    public const SDL_RWOPS_JNIFILE                                 = 3;
    public const SDL_RWOPS_MEMORY                                  = 4;
    public const SDL_RWOPS_MEMORY_RO                               = 5;
    public const SDL_STANDARD_GRAVITY                              = 9.80665;
    public const SDL_NONSHAPEABLE_WINDOW                           = -1;
    public const SDL_INVALID_SHAPE_ARGUMENT                        = -2;
    public const SDL_WINDOW_LACKS_SHAPE                            = -3;
    public const SDL_SWSURFACE                                     = 0;
    public const SDL_PREALLOC                                      = 0x00000001;
    public const SDL_RLEACCEL                                      = 0x00000002;
    public const SDL_DONTFREE                                      = 0x00000004;
    public const SDL_SIMD_ALIGNED                                  = 0x00000008;
    public const SDL_WINDOWPOS_UNDEFINED_MASK                      = 0x1FFF0000;
    public const SDL_WINDOWPOS_UNDEFINED                           = self::SDL_WINDOWPOS_UNDEFINED_MASK
                                                                   | 0;
    public const SDL_WINDOWPOS_CENTERED_MASK                       = 0x2FFF0000;
    public const SDL_WINDOWPOS_CENTERED                            = self::SDL_WINDOWPOS_CENTERED_MASK
                                                                   | 0;

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
     * @param non-empty-string|null $binary
     * @param non-empty-string|null $version
     * @param bool $init
     * @param Platform|null $platform
     * @param CacheInterface|null $cache
     * @throws DirectiveDefinitionExceptionInterface
     * @throws InvalidArgumentException
     * @psalm-suppress InvalidThrow
     * @psalm-suppress MixedArgument
     */
    public function __construct(
        ?string $binary = null,
        ?string $version = null,
        bool $init = false,
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

        if ($init) {
            $this->ffi->SDL_Init(self::SDL_INIT_EVERYTHING);
        }
    }

    /**
     * @return non-empty-string
     * @psalm-suppress MoreSpecificReturnType
     * @psalm-suppress LessSpecificReturnStatement
     */
    private function detectBinary(): string
    {
        return match (\PHP_OS_FAMILY) {
            'Windows' => Locator::resolve('SDL2.dll')
                ?? throw new LoadingException(<<<'error'
                Windows OS requires a reference to SDL2 (SDL2.dll) library.
                Please download the required version of the library from
                the official repository: https://github.com/libsdl-org/SDL/releases
                error),
            'Linux', 'BSD', 'Solaris' => Locator::resolve('libSDL2.so', 'libSDL2-2.0.so.0')
                ?? throw new LoadingException(<<<'error'
                Could not load SDL2 (libSDL2-2.0.so.0) library.
                Please download the required version of the library from
                the official repository: https://github.com/libsdl-org/SDL/releases
                error),
            'Darwin' => Locator::resolve('libSDL2-2.0.0.dylib')
                ?? throw new LoadingException(<<<'error'
                Could not load SDL2 (libSDL2-2.0.0.dylib) library.
                Please download the required version of the library from
                the official repository: https://github.com/libsdl-org/SDL/releases
                error),
            default => throw new PlatformException(
                'Could not detect library for ' . \PHP_OS
            )
        };
    }

    /**
     * @param non-empty-string $binary
     * @return non-empty-string
     *
     * @psalm-suppress all
     */
    private function detectVersion(string $binary): string
    {
        /** @var \FFI|object $ffi */
        $ffi = \FFI::cdef(<<<'CLANG'
        typedef struct SDL_version {
            uint8_t major;
            uint8_t minor;
            uint8_t patch;
        } SDL_version;
        extern void SDL_GetVersion(SDL_version * ver);
        CLANG, $binary);

        $version = $ffi->new('SDL_version');

        $ffi->SDL_GetVersion(\FFI::addr($version));

        return \vsprintf('%d.%d.%d', [
            $version->major,
            $version->minor,
            $version->patch,
        ]);
    }

    /**
     * @param Platform|null $platform
     * @return non-empty-string
     * @throws DirectiveDefinitionExceptionInterface
     * @throws InvalidArgumentException
     *
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
     * @return HeaderInterface
     * @throws DirectiveDefinitionExceptionInterface
     */
    private function getHeaders(?Platform $platform): HeaderInterface
    {
        return SDL2Headers::create(
            platform: match ($platform) {
                Platform::WINDOWS => HeadersPlatform::WINDOWS,
                Platform::LINUX => HeadersPlatform::LINUX,
                Platform::DARWIN => HeadersPlatform::DARWIN,
                Platform::FREEBSD => HeadersPlatform::FREEBSD,
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
        return \hash('xxh128', \sprintf('sdl-%s|%s', $this->version, $platform?->name ?? '<unknown>'));
    }

    /**
     * @return non-empty-string|null
     */
    public function getErrorMessage(): ?string
    {
        $string = $this->ffi->new('char[1024]');
        $this->ffi->SDL_GetErrorMsg(\FFI::addr($string[0]), 1024);

        $result = \FFI::string($string);

        return \trim($result) !== '' ? $result : null;
    }

    /**
     * @return void
     */
    public function __destruct()
    {
        $this->ffi->SDL_Quit();
    }
}

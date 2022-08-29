<?php

declare(strict_types=1);

namespace Bic\UI\SDL2;

use Bic\Lib\SDL2;
use Bic\Lib\SDL2\KeyMod;
use Bic\Lib\SDL2\ScanCode;
use Bic\UI\Keyboard\Key;
use Bic\UI\Keyboard\KeyInterface;
use Bic\UI\Keyboard\Modifier;
use Bic\UI\Keyboard\UserKey;
use Bic\UI\Mouse\Button;
use Bic\UI\Mouse\ButtonInterface;
use Bic\UI\Mouse\Wheel;
use Bic\UI\Mouse\UserButton;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Bic\UI\SDL2
 *
 * @package ui-sdl2
 */
final class Mapping
{
    /**
     * @var array<ScanCode::SDL_SCANCODE_*, Key>
     */
    private const KEY_CODES = [
        ScanCode::SDL_SCANCODE_A                  => Key::A,
        ScanCode::SDL_SCANCODE_B                  => Key::B,
        ScanCode::SDL_SCANCODE_C                  => Key::C,
        ScanCode::SDL_SCANCODE_D                  => Key::D,
        ScanCode::SDL_SCANCODE_E                  => Key::E,
        ScanCode::SDL_SCANCODE_F                  => Key::F,
        ScanCode::SDL_SCANCODE_G                  => Key::G,
        ScanCode::SDL_SCANCODE_H                  => Key::H,
        ScanCode::SDL_SCANCODE_I                  => Key::I,
        ScanCode::SDL_SCANCODE_J                  => Key::J,
        ScanCode::SDL_SCANCODE_K                  => Key::K,
        ScanCode::SDL_SCANCODE_L                  => Key::L,
        ScanCode::SDL_SCANCODE_M                  => Key::M,
        ScanCode::SDL_SCANCODE_N                  => Key::N,
        ScanCode::SDL_SCANCODE_O                  => Key::O,
        ScanCode::SDL_SCANCODE_P                  => Key::P,
        ScanCode::SDL_SCANCODE_Q                  => Key::Q,
        ScanCode::SDL_SCANCODE_R                  => Key::R,
        ScanCode::SDL_SCANCODE_S                  => Key::S,
        ScanCode::SDL_SCANCODE_T                  => Key::T,
        ScanCode::SDL_SCANCODE_U                  => Key::U,
        ScanCode::SDL_SCANCODE_V                  => Key::V,
        ScanCode::SDL_SCANCODE_W                  => Key::W,
        ScanCode::SDL_SCANCODE_X                  => Key::X,
        ScanCode::SDL_SCANCODE_Y                  => Key::Y,
        ScanCode::SDL_SCANCODE_Z                  => Key::Z,
        ScanCode::SDL_SCANCODE_1                  => Key::KEY_1,
        ScanCode::SDL_SCANCODE_2                  => Key::KEY_2,
        ScanCode::SDL_SCANCODE_3                  => Key::KEY_3,
        ScanCode::SDL_SCANCODE_4                  => Key::KEY_4,
        ScanCode::SDL_SCANCODE_5                  => Key::KEY_5,
        ScanCode::SDL_SCANCODE_6                  => Key::KEY_6,
        ScanCode::SDL_SCANCODE_7                  => Key::KEY_7,
        ScanCode::SDL_SCANCODE_8                  => Key::KEY_8,
        ScanCode::SDL_SCANCODE_9                  => Key::KEY_9,
        ScanCode::SDL_SCANCODE_0                  => Key::KEY_0,
        ScanCode::SDL_SCANCODE_RETURN             => Key::RETURN,
        ScanCode::SDL_SCANCODE_ESCAPE             => Key::ESCAPE,
        ScanCode::SDL_SCANCODE_BACKSPACE          => Key::BACKSPACE,
        ScanCode::SDL_SCANCODE_TAB                => Key::TAB,
        ScanCode::SDL_SCANCODE_SPACE              => Key::SPACE,
        ScanCode::SDL_SCANCODE_MINUS              => Key::MINUS,
        ScanCode::SDL_SCANCODE_EQUALS             => Key::EQUALS,
        ScanCode::SDL_SCANCODE_LEFTBRACKET        => Key::LEFTBRACKET,
        ScanCode::SDL_SCANCODE_RIGHTBRACKET       => Key::RIGHTBRACKET,
        ScanCode::SDL_SCANCODE_BACKSLASH          => Key::BACKSLASH,
        ScanCode::SDL_SCANCODE_NONUSHASH          => Key::NONUSHASH,
        ScanCode::SDL_SCANCODE_SEMICOLON          => Key::SEMICOLON,
        ScanCode::SDL_SCANCODE_APOSTROPHE         => Key::APOSTROPHE,
        ScanCode::SDL_SCANCODE_GRAVE              => Key::GRAVE,
        ScanCode::SDL_SCANCODE_COMMA              => Key::COMMA,
        ScanCode::SDL_SCANCODE_PERIOD             => Key::PERIOD,
        ScanCode::SDL_SCANCODE_SLASH              => Key::SLASH,
        ScanCode::SDL_SCANCODE_CAPSLOCK           => Key::CAPSLOCK,
        ScanCode::SDL_SCANCODE_F1                 => Key::F1,
        ScanCode::SDL_SCANCODE_F2                 => Key::F2,
        ScanCode::SDL_SCANCODE_F3                 => Key::F3,
        ScanCode::SDL_SCANCODE_F4                 => Key::F4,
        ScanCode::SDL_SCANCODE_F5                 => Key::F5,
        ScanCode::SDL_SCANCODE_F6                 => Key::F6,
        ScanCode::SDL_SCANCODE_F7                 => Key::F7,
        ScanCode::SDL_SCANCODE_F8                 => Key::F8,
        ScanCode::SDL_SCANCODE_F9                 => Key::F9,
        ScanCode::SDL_SCANCODE_F10                => Key::F10,
        ScanCode::SDL_SCANCODE_F11                => Key::F11,
        ScanCode::SDL_SCANCODE_F12                => Key::F12,
        ScanCode::SDL_SCANCODE_PRINTSCREEN        => Key::PRINTSCREEN,
        ScanCode::SDL_SCANCODE_SCROLLLOCK         => Key::SCROLLLOCK,
        ScanCode::SDL_SCANCODE_PAUSE              => Key::PAUSE,
        ScanCode::SDL_SCANCODE_INSERT             => Key::INSERT,
        ScanCode::SDL_SCANCODE_HOME               => Key::HOME,
        ScanCode::SDL_SCANCODE_PAGEUP             => Key::PAGEUP,
        ScanCode::SDL_SCANCODE_DELETE             => Key::DELETE,
        ScanCode::SDL_SCANCODE_END                => Key::END,
        ScanCode::SDL_SCANCODE_PAGEDOWN           => Key::PAGEDOWN,
        ScanCode::SDL_SCANCODE_RIGHT              => Key::RIGHT,
        ScanCode::SDL_SCANCODE_LEFT               => Key::LEFT,
        ScanCode::SDL_SCANCODE_DOWN               => Key::DOWN,
        ScanCode::SDL_SCANCODE_UP                 => Key::UP,
        ScanCode::SDL_SCANCODE_NUMLOCKCLEAR       => Key::NUMLOCKCLEAR,
        ScanCode::SDL_SCANCODE_KP_DIVIDE          => Key::KP_DIVIDE,
        ScanCode::SDL_SCANCODE_KP_MULTIPLY        => Key::KP_MULTIPLY,
        ScanCode::SDL_SCANCODE_KP_MINUS           => Key::KP_MINUS,
        ScanCode::SDL_SCANCODE_KP_PLUS            => Key::KP_PLUS,
        ScanCode::SDL_SCANCODE_KP_ENTER           => Key::KP_ENTER,
        ScanCode::SDL_SCANCODE_KP_1               => Key::KP_1,
        ScanCode::SDL_SCANCODE_KP_2               => Key::KP_2,
        ScanCode::SDL_SCANCODE_KP_3               => Key::KP_3,
        ScanCode::SDL_SCANCODE_KP_4               => Key::KP_4,
        ScanCode::SDL_SCANCODE_KP_5               => Key::KP_5,
        ScanCode::SDL_SCANCODE_KP_6               => Key::KP_6,
        ScanCode::SDL_SCANCODE_KP_7               => Key::KP_7,
        ScanCode::SDL_SCANCODE_KP_8               => Key::KP_8,
        ScanCode::SDL_SCANCODE_KP_9               => Key::KP_9,
        ScanCode::SDL_SCANCODE_KP_0               => Key::KP_0,
        ScanCode::SDL_SCANCODE_KP_PERIOD          => Key::KP_PERIOD,
        ScanCode::SDL_SCANCODE_NONUSBACKSLASH     => Key::NONUSBACKSLASH,
        ScanCode::SDL_SCANCODE_APPLICATION        => Key::APPLICATION,
        ScanCode::SDL_SCANCODE_POWER              => Key::POWER,
        ScanCode::SDL_SCANCODE_KP_EQUALS          => Key::KP_EQUALS,
        ScanCode::SDL_SCANCODE_F13                => Key::F13,
        ScanCode::SDL_SCANCODE_F14                => Key::F14,
        ScanCode::SDL_SCANCODE_F15                => Key::F15,
        ScanCode::SDL_SCANCODE_F16                => Key::F16,
        ScanCode::SDL_SCANCODE_F17                => Key::F17,
        ScanCode::SDL_SCANCODE_F18                => Key::F18,
        ScanCode::SDL_SCANCODE_F19                => Key::F19,
        ScanCode::SDL_SCANCODE_F20                => Key::F20,
        ScanCode::SDL_SCANCODE_F21                => Key::F21,
        ScanCode::SDL_SCANCODE_F22                => Key::F22,
        ScanCode::SDL_SCANCODE_F23                => Key::F23,
        ScanCode::SDL_SCANCODE_F24                => Key::F24,
        ScanCode::SDL_SCANCODE_EXECUTE            => Key::EXECUTE,
        ScanCode::SDL_SCANCODE_HELP               => Key::HELP,
        ScanCode::SDL_SCANCODE_MENU               => Key::MENU,
        ScanCode::SDL_SCANCODE_SELECT             => Key::SELECT,
        ScanCode::SDL_SCANCODE_STOP               => Key::STOP,
        ScanCode::SDL_SCANCODE_AGAIN              => Key::AGAIN,
        ScanCode::SDL_SCANCODE_UNDO               => Key::UNDO,
        ScanCode::SDL_SCANCODE_CUT                => Key::CUT,
        ScanCode::SDL_SCANCODE_COPY               => Key::COPY,
        ScanCode::SDL_SCANCODE_PASTE              => Key::PASTE,
        ScanCode::SDL_SCANCODE_FIND               => Key::FIND,
        ScanCode::SDL_SCANCODE_MUTE               => Key::MUTE,
        ScanCode::SDL_SCANCODE_VOLUMEUP           => Key::VOLUMEUP,
        ScanCode::SDL_SCANCODE_VOLUMEDOWN         => Key::VOLUMEDOWN,
        ScanCode::SDL_SCANCODE_KP_COMMA           => Key::KP_COMMA,
        ScanCode::SDL_SCANCODE_KP_EQUALSAS400     => Key::KP_EQUALSAS400,
        ScanCode::SDL_SCANCODE_INTERNATIONAL1     => Key::INTERNATIONAL1,
        ScanCode::SDL_SCANCODE_INTERNATIONAL2     => Key::INTERNATIONAL2,
        ScanCode::SDL_SCANCODE_INTERNATIONAL3     => Key::INTERNATIONAL3,
        ScanCode::SDL_SCANCODE_INTERNATIONAL4     => Key::INTERNATIONAL4,
        ScanCode::SDL_SCANCODE_INTERNATIONAL5     => Key::INTERNATIONAL5,
        ScanCode::SDL_SCANCODE_INTERNATIONAL6     => Key::INTERNATIONAL6,
        ScanCode::SDL_SCANCODE_INTERNATIONAL7     => Key::INTERNATIONAL7,
        ScanCode::SDL_SCANCODE_INTERNATIONAL8     => Key::INTERNATIONAL8,
        ScanCode::SDL_SCANCODE_INTERNATIONAL9     => Key::INTERNATIONAL9,
        ScanCode::SDL_SCANCODE_LANG1              => Key::LANG1,
        ScanCode::SDL_SCANCODE_LANG2              => Key::LANG2,
        ScanCode::SDL_SCANCODE_LANG3              => Key::LANG3,
        ScanCode::SDL_SCANCODE_LANG4              => Key::LANG4,
        ScanCode::SDL_SCANCODE_LANG5              => Key::LANG5,
        ScanCode::SDL_SCANCODE_LANG6              => Key::LANG6,
        ScanCode::SDL_SCANCODE_LANG7              => Key::LANG7,
        ScanCode::SDL_SCANCODE_LANG8              => Key::LANG8,
        ScanCode::SDL_SCANCODE_LANG9              => Key::LANG9,
        ScanCode::SDL_SCANCODE_ALTERASE           => Key::ALTERASE,
        ScanCode::SDL_SCANCODE_SYSREQ             => Key::SYSREQ,
        ScanCode::SDL_SCANCODE_CANCEL             => Key::CANCEL,
        ScanCode::SDL_SCANCODE_CLEAR              => Key::CLEAR,
        ScanCode::SDL_SCANCODE_PRIOR              => Key::PRIOR,
        ScanCode::SDL_SCANCODE_RETURN2            => Key::RETURN2,
        ScanCode::SDL_SCANCODE_SEPARATOR          => Key::SEPARATOR,
        ScanCode::SDL_SCANCODE_OUT                => Key::OUT,
        ScanCode::SDL_SCANCODE_OPER               => Key::OPER,
        ScanCode::SDL_SCANCODE_CLEARAGAIN         => Key::CLEARAGAIN,
        ScanCode::SDL_SCANCODE_CRSEL              => Key::CRSEL,
        ScanCode::SDL_SCANCODE_EXSEL              => Key::EXSEL,
        ScanCode::SDL_SCANCODE_KP_00              => Key::KP_00,
        ScanCode::SDL_SCANCODE_KP_000             => Key::KP_000,
        ScanCode::SDL_SCANCODE_THOUSANDSSEPARATOR => Key::THOUSANDSSEPARATOR,
        ScanCode::SDL_SCANCODE_DECIMALSEPARATOR   => Key::DECIMALSEPARATOR,
        ScanCode::SDL_SCANCODE_CURRENCYUNIT       => Key::CURRENCYUNIT,
        ScanCode::SDL_SCANCODE_CURRENCYSUBUNIT    => Key::CURRENCYSUBUNIT,
        ScanCode::SDL_SCANCODE_KP_LEFTPAREN       => Key::KP_LEFTPAREN,
        ScanCode::SDL_SCANCODE_KP_RIGHTPAREN      => Key::KP_RIGHTPAREN,
        ScanCode::SDL_SCANCODE_KP_LEFTBRACE       => Key::KP_LEFTBRACE,
        ScanCode::SDL_SCANCODE_KP_RIGHTBRACE      => Key::KP_RIGHTBRACE,
        ScanCode::SDL_SCANCODE_KP_TAB             => Key::KP_TAB,
        ScanCode::SDL_SCANCODE_KP_BACKSPACE       => Key::KP_BACKSPACE,
        ScanCode::SDL_SCANCODE_KP_A               => Key::KP_A,
        ScanCode::SDL_SCANCODE_KP_B               => Key::KP_B,
        ScanCode::SDL_SCANCODE_KP_C               => Key::KP_C,
        ScanCode::SDL_SCANCODE_KP_D               => Key::KP_D,
        ScanCode::SDL_SCANCODE_KP_E               => Key::KP_E,
        ScanCode::SDL_SCANCODE_KP_F               => Key::KP_F,
        ScanCode::SDL_SCANCODE_KP_XOR             => Key::KP_XOR,
        ScanCode::SDL_SCANCODE_KP_POWER           => Key::KP_POWER,
        ScanCode::SDL_SCANCODE_KP_PERCENT         => Key::KP_PERCENT,
        ScanCode::SDL_SCANCODE_KP_LESS            => Key::KP_LESS,
        ScanCode::SDL_SCANCODE_KP_GREATER         => Key::KP_GREATER,
        ScanCode::SDL_SCANCODE_KP_AMPERSAND       => Key::KP_AMPERSAND,
        ScanCode::SDL_SCANCODE_KP_DBLAMPERSAND    => Key::KP_DBLAMPERSAND,
        ScanCode::SDL_SCANCODE_KP_VERTICALBAR     => Key::KP_VERTICALBAR,
        ScanCode::SDL_SCANCODE_KP_DBLVERTICALBAR  => Key::KP_DBLVERTICALBAR,
        ScanCode::SDL_SCANCODE_KP_COLON           => Key::KP_COLON,
        ScanCode::SDL_SCANCODE_KP_HASH            => Key::KP_HASH,
        ScanCode::SDL_SCANCODE_KP_SPACE           => Key::KP_SPACE,
        ScanCode::SDL_SCANCODE_KP_AT              => Key::KP_AT,
        ScanCode::SDL_SCANCODE_KP_EXCLAM          => Key::KP_EXCLAM,
        ScanCode::SDL_SCANCODE_KP_MEMSTORE        => Key::KP_MEMSTORE,
        ScanCode::SDL_SCANCODE_KP_MEMRECALL       => Key::KP_MEMRECALL,
        ScanCode::SDL_SCANCODE_KP_MEMCLEAR        => Key::KP_MEMCLEAR,
        ScanCode::SDL_SCANCODE_KP_MEMADD          => Key::KP_MEMADD,
        ScanCode::SDL_SCANCODE_KP_MEMSUBTRACT     => Key::KP_MEMSUBTRACT,
        ScanCode::SDL_SCANCODE_KP_MEMMULTIPLY     => Key::KP_MEMMULTIPLY,
        ScanCode::SDL_SCANCODE_KP_MEMDIVIDE       => Key::KP_MEMDIVIDE,
        ScanCode::SDL_SCANCODE_KP_PLUSMINUS       => Key::KP_PLUSMINUS,
        ScanCode::SDL_SCANCODE_KP_CLEAR           => Key::KP_CLEAR,
        ScanCode::SDL_SCANCODE_KP_CLEARENTRY      => Key::KP_CLEARENTRY,
        ScanCode::SDL_SCANCODE_KP_BINARY          => Key::KP_BINARY,
        ScanCode::SDL_SCANCODE_KP_OCTAL           => Key::KP_OCTAL,
        ScanCode::SDL_SCANCODE_KP_DECIMAL         => Key::KP_DECIMAL,
        ScanCode::SDL_SCANCODE_KP_HEXADECIMAL     => Key::KP_HEXADECIMAL,
        ScanCode::SDL_SCANCODE_LCTRL              => Key::LCTRL,
        ScanCode::SDL_SCANCODE_LSHIFT             => Key::LSHIFT,
        ScanCode::SDL_SCANCODE_LALT               => Key::LALT,
        ScanCode::SDL_SCANCODE_LGUI               => Key::LGUI,
        ScanCode::SDL_SCANCODE_RCTRL              => Key::RCTRL,
        ScanCode::SDL_SCANCODE_RSHIFT             => Key::RSHIFT,
        ScanCode::SDL_SCANCODE_RALT               => Key::RALT,
        ScanCode::SDL_SCANCODE_RGUI               => Key::RGUI,
        ScanCode::SDL_SCANCODE_MODE               => Key::MODE,
        ScanCode::SDL_SCANCODE_AUDIONEXT          => Key::AUDIONEXT,
        ScanCode::SDL_SCANCODE_AUDIOPREV          => Key::AUDIOPREV,
        ScanCode::SDL_SCANCODE_AUDIOSTOP          => Key::AUDIOSTOP,
        ScanCode::SDL_SCANCODE_AUDIOPLAY          => Key::AUDIOPLAY,
        ScanCode::SDL_SCANCODE_AUDIOMUTE          => Key::AUDIOMUTE,
        ScanCode::SDL_SCANCODE_MEDIASELECT        => Key::MEDIASELECT,
        ScanCode::SDL_SCANCODE_WWW                => Key::WWW,
        ScanCode::SDL_SCANCODE_MAIL               => Key::MAIL,
        ScanCode::SDL_SCANCODE_CALCULATOR         => Key::CALCULATOR,
        ScanCode::SDL_SCANCODE_COMPUTER           => Key::COMPUTER,
        ScanCode::SDL_SCANCODE_AC_SEARCH          => Key::AC_SEARCH,
        ScanCode::SDL_SCANCODE_AC_HOME            => Key::AC_HOME,
        ScanCode::SDL_SCANCODE_AC_BACK            => Key::AC_BACK,
        ScanCode::SDL_SCANCODE_AC_FORWARD         => Key::AC_FORWARD,
        ScanCode::SDL_SCANCODE_AC_STOP            => Key::AC_STOP,
        ScanCode::SDL_SCANCODE_AC_REFRESH         => Key::AC_REFRESH,
        ScanCode::SDL_SCANCODE_AC_BOOKMARKS       => Key::AC_BOOKMARKS,
        ScanCode::SDL_SCANCODE_BRIGHTNESSDOWN     => Key::BRIGHTNESSDOWN,
        ScanCode::SDL_SCANCODE_BRIGHTNESSUP       => Key::BRIGHTNESSUP,
        ScanCode::SDL_SCANCODE_DISPLAYSWITCH      => Key::DISPLAYSWITCH,
        ScanCode::SDL_SCANCODE_KBDILLUMTOGGLE     => Key::KBDILLUMTOGGLE,
        ScanCode::SDL_SCANCODE_KBDILLUMDOWN       => Key::KBDILLUMDOWN,
        ScanCode::SDL_SCANCODE_KBDILLUMUP         => Key::KBDILLUMUP,
        ScanCode::SDL_SCANCODE_EJECT              => Key::EJECT,
        ScanCode::SDL_SCANCODE_SLEEP              => Key::SLEEP,
        ScanCode::SDL_SCANCODE_APP1               => Key::APP1,
        ScanCode::SDL_SCANCODE_APP2               => Key::APP2,
        ScanCode::SDL_SCANCODE_AUDIOREWIND        => Key::AUDIOREWIND,
        ScanCode::SDL_SCANCODE_AUDIOFASTFORWARD   => Key::AUDIOFASTFORWARD,
        ScanCode::SDL_SCANCODE_SOFTLEFT           => Key::SOFTLEFT,
        ScanCode::SDL_SCANCODE_SOFTRIGHT          => Key::SOFTRIGHT,
        ScanCode::SDL_SCANCODE_CALL               => Key::CALL,
        ScanCode::SDL_SCANCODE_ENDCALL            => Key::ENDCALL,
    ];

    /**
     * @var array<KeyMod::KMOD_*, Modifier::*>
     */
    public const KEY_MODIFIERS = [
        KeyMod::KMOD_NONE     => Modifier::NONE,
        KeyMod::KMOD_LSHIFT   => Modifier::LSHIFT,
        KeyMod::KMOD_RSHIFT   => Modifier::RSHIFT,
        KeyMod::KMOD_LCTRL    => Modifier::LCTRL,
        KeyMod::KMOD_RCTRL    => Modifier::RCTRL,
        KeyMod::KMOD_LALT     => Modifier::LALT,
        KeyMod::KMOD_RALT     => Modifier::RALT,
        KeyMod::KMOD_LGUI     => Modifier::LGUI,
        KeyMod::KMOD_RGUI     => Modifier::RGUI,
        KeyMod::KMOD_NUM      => Modifier::NUM,
        KeyMod::KMOD_CAPS     => Modifier::CAPS,
        KeyMod::KMOD_MODE     => Modifier::MODE,
        KeyMod::KMOD_SCROLL   => Modifier::SCROLL,
    ];

    /**
     * @var array<int-mask-of<KeyMod::KMOD_*>, int-mask-of<Modifier::*>>
     */
    private static array $modifiers = [];

    /**
     * @param ScanCode::SDL_SCANCODE_* $code
     * @return KeyInterface
     */
    public static function key(int $code): KeyInterface
    {
        return self::KEY_CODES[$code] ?? UserKey::create($code);
    }

    /**
     * @param int-mask-of<KeyMod::KMOD_*> $modifiers
     * @return int-mask-of<Modifier::*>
     * @psalm-suppress MoreSpecificReturnType
     * @psalm-suppress LessSpecificReturnStatement
     */
    public static function mod(int $modifiers): int
    {
        if (!isset(self::$modifiers[$modifiers])) {
            self::$modifiers[$modifiers] = Modifier::NONE;

            foreach (self::KEY_MODIFIERS as $from => $to) {
                if (($modifiers & $from) === $from) {
                    self::$modifiers[$modifiers] |= $to;
                }
            }
        }

        return self::$modifiers[$modifiers];
    }

    /**
     * @psalm-import-type ButtonID from ButtonInterface
     * @param ButtonID $code
     * @return ButtonInterface
     */
    public static function button(int $code): ButtonInterface
    {
        return match ($code) {
            SDL2::SDL_BUTTON_LEFT => Button::LEFT,
            SDL2::SDL_BUTTON_MIDDLE => Button::MIDDLE,
            SDL2::SDL_BUTTON_RIGHT => Button::RIGHT,
            SDL2::SDL_BUTTON_X1 => Button::X1,
            SDL2::SDL_BUTTON_X2 => Button::X2,
            default => UserButton::create($code),
        };
    }

    /**
     * @param int $x
     * @param int $y
     * @return Wheel
     */
    public static function wheel(int $x, int $y): Wheel
    {
        return match ($x) {
            1 => Wheel::RIGHT,
            -1 => Wheel::LEFT,
            default => match ($y) {
                1 => Wheel::UP,
                -1 => Wheel::DOWN,
            }
        };
    }
}

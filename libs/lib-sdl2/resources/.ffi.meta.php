<?php

/**
 * This file is part of Bic Engine package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Bic\Lib\SDL2\CData;

use Bic\Lib\SDL2\EventType;
use Bic\Lib\SDL2\JoystickPowerLevel;
use Bic\Lib\SDL2\KeyCode;
use Bic\Lib\SDL2\KeyMod;
use Bic\Lib\SDL2\ScanCode;
use Bic\Lib\SDL2\WindowEventID;
use FFI\CData;
use JetBrains\PhpStorm\ExpectedValues;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Bic\Lib\SDL2\CData
 */
abstract class __SDL_AbstractEvent extends CData
{
    /**
     * Event type, shared with all events.
     */
    #[ExpectedValues(valuesFromClass: EventType::class)]
    public readonly int $type;
}

/**
 * @internal This is autocompletion stub class for FFI struct
 */
class SDL_CommonEvent extends __SDL_AbstractEvent
{
    /**
     * In milliseconds, populated using SDL_GetTicks()
     */
    public readonly int $timestamp;
}

/**
 * @internal This is autocompletion stub class for FFI struct
 */
class SDL_DisplayEvent extends __SDL_AbstractEvent
{
    public readonly int $timestamp;
    public readonly int $display;
    public readonly int $event;
    public readonly int $padding1;
    public readonly int $padding2;
    public readonly int $padding3;
    public readonly int $data1;
}

/**
 * @internal This is autocompletion stub class for FFI struct
 */
class SDL_WindowEvent extends __SDL_AbstractEvent
{
    public readonly int $timestamp;
    public readonly int $windowID;
    #[ExpectedValues(valuesFromClass: WindowEventID::class)]
    public readonly int $event;
    public readonly int $padding1;
    public readonly int $padding2;
    public readonly int $padding3;
    public readonly int $data1;
    public readonly int $data2;
}

/**
 * @internal This is autocompletion stub class for FFI struct
 */
class SDL_Keysym extends CData
{
    #[ExpectedValues(valuesFromClass: ScanCode::class)]
    public readonly int $scancode;
    #[ExpectedValues(valuesFromClass: KeyCode::class)]
    public readonly int $sym;
    #[ExpectedValues(flagsFromClass: KeyMod::class)]
    public readonly int $mod;
    public readonly int $unused;
}

/**
 * @internal This is autocompletion stub class for FFI struct
 */
class SDL_KeyboardEvent extends __SDL_AbstractEvent
{
    public readonly int $timestamp;
    public readonly int $windowID;
    public readonly int $state;
    public readonly int $repeat;
    public readonly int $padding2;
    public readonly int $padding3;
    public SDL_Keysym $keysym;
}

/**
 * @internal This is autocompletion stub class for FFI struct
 */
class SDL_TextEditingEvent extends __SDL_AbstractEvent
{
    public readonly int $timestamp;
    public readonly int $windowID;
    /** @var array<string> */
    public readonly array $text;
    public readonly int $start;
    public readonly int $length;
}

/**
 * @internal This is autocompletion stub class for FFI struct
 */
class SDL_TextEditingExtEvent extends __SDL_AbstractEvent
{
    public readonly int $timestamp;
    public readonly int $windowID;
    public readonly string $text;
    public readonly int $start;
    public readonly int $length;
}

/**
 * @internal This is autocompletion stub class for FFI struct
 */
class SDL_TextInputEvent extends __SDL_AbstractEvent
{
    public readonly int $timestamp;
    public readonly int $windowID;
    /** @var array<string> */
    public readonly array $text;
}

/**
 * @internal This is autocompletion stub class for FFI struct
 */
class SDL_MouseMotionEvent extends __SDL_AbstractEvent
{
    public readonly int $timestamp;
    public readonly int $windowID;
    public readonly int $which;
    public readonly int $state;
    public readonly int $x;
    public readonly int $y;
    public readonly int $xrel;
    public readonly int $yrel;
}

/**
 * @internal This is autocompletion stub class for FFI struct
 */
class SDL_MouseButtonEvent extends __SDL_AbstractEvent
{
    public readonly int $timestamp;
    public readonly int $windowID;
    public readonly int $which;
    public readonly int $button;
    public readonly int $state;
    public readonly int $clicks;
    public readonly int $padding1;
    public readonly int $x;
    public readonly int $y;
}

/**
 * @internal This is autocompletion stub class for FFI struct
 */
class SDL_MouseWheelEvent extends __SDL_AbstractEvent
{
    public readonly int $timestamp;
    public readonly int $windowID;
    public readonly int $which;
    public readonly int $x;
    public readonly int $y;
    public readonly int $direction;
    public readonly float $preciseX;
    public readonly float $preciseY;
}

/**
 * @internal This is autocompletion stub class for FFI struct
 */
class SDL_JoyAxisEvent extends __SDL_AbstractEvent
{
    public readonly int $timestamp;
    public readonly int $which;
    public readonly int $axis;
    public readonly int $padding1;
    public readonly int $padding2;
    public readonly int $padding3;
    public readonly int $value;
    public readonly int $padding4;
}

/**
 * @internal This is autocompletion stub class for FFI struct
 */
class SDL_JoyBallEvent extends __SDL_AbstractEvent
{
    public readonly int $timestamp;
    public readonly int $which;
    public readonly int $ball;
    public readonly int $padding1;
    public readonly int $padding2;
    public readonly int $padding3;
    public readonly int $xrel;
    public readonly int $yrel;
}

/**
 * @internal This is autocompletion stub class for FFI struct
 */
class SDL_JoyHatEvent extends __SDL_AbstractEvent
{
    public readonly int $timestamp;
    public readonly int $which;
    public readonly int $hat;
    public readonly int $value;
    public readonly int $padding1;
    public readonly int $padding2;
}

/**
 * @internal This is autocompletion stub class for FFI struct
 */
class SDL_JoyButtonEvent extends __SDL_AbstractEvent
{
    public readonly int $timestamp;
    public readonly int $which;
    public readonly int $button;
    public readonly int $state;
    public readonly int $padding1;
    public readonly int $padding2;
}

/**
 * @internal This is autocompletion stub class for FFI struct
 */
class SDL_JoyDeviceEvent extends __SDL_AbstractEvent
{
    public readonly int $timestamp;
    public readonly int $which;
}

/**
 * @internal This is autocompletion stub class for FFI struct
 */
class SDL_JoyBatteryEvent extends __SDL_AbstractEvent
{
    public readonly int $timestamp;
    public readonly int $which;
    #[ExpectedValues(valuesFromClass: JoystickPowerLevel::class)]
    public readonly int $level;
}

/**
 * @internal This is autocompletion stub class for FFI struct
 */
class SDL_ControllerAxisEvent extends __SDL_AbstractEvent
{
    public readonly int $timestamp;
    public readonly int $which;
    public readonly int $axis;
    public readonly int $padding1;
    public readonly int $padding2;
    public readonly int $padding3;
    public readonly int $value;
    public readonly int $padding4;
}

/**
 * @internal This is autocompletion stub class for FFI struct
 */
class SDL_ControllerButtonEvent extends __SDL_AbstractEvent
{
    public readonly int $timestamp;
    public readonly int $which;
    public readonly int $button;
    public readonly int $state;
    public readonly int $padding1;
    public readonly int $padding2;
}

/**
 * @internal This is autocompletion stub class for FFI struct
 */
class SDL_ControllerDeviceEvent extends __SDL_AbstractEvent
{
    public readonly int $timestamp;
    public readonly int $which;
}

/**
 * @internal This is autocompletion stub class for FFI struct
 */
class SDL_ControllerTouchpadEvent extends __SDL_AbstractEvent
{
    public readonly int $timestamp;
    public readonly int $which;
    public readonly int $touchpad;
    public readonly int $finger;
    public readonly float $x;
    public readonly float $y;
    public readonly float $pressure;
}

/**
 * @internal This is autocompletion stub class for FFI struct
 */
class SDL_ControllerSensorEvent extends __SDL_AbstractEvent
{
    public readonly int $timestamp;
    public readonly int $which;
    public readonly int $sensor;
    /** @var array{float, float, float} */
    public readonly array $data;
}

/**
 * @internal This is autocompletion stub class for FFI struct
 */
class SDL_AudioDeviceEvent extends __SDL_AbstractEvent
{
    public readonly int $timestamp;
    public readonly int $which;
    public readonly int $iscapture;
    public readonly int $padding1;
    public readonly int $padding2;
    public readonly int $padding3;
}

/**
 * @internal This is autocompletion stub class for FFI struct
 */
class SDL_TouchFingerEvent extends __SDL_AbstractEvent
{
    public readonly int $timestamp;
    public readonly int $touchId;
    public readonly int $fingerId;
    public readonly float $x;
    public readonly float $y;
    public readonly float $dx;
    public readonly float $dy;
    public readonly float $pressure;
    public readonly int $windowID;
}

/**
 * @internal This is autocompletion stub class for FFI struct
 */
class SDL_MultiGestureEvent extends __SDL_AbstractEvent
{
    public readonly int $timestamp;
    public readonly int $touchId;
    public readonly float $dTheta;
    public readonly float $dDist;
    public readonly float $x;
    public readonly float $y;
    public readonly int $numFingers;
    public readonly int $padding;
}

/**
 * @internal This is autocompletion stub class for FFI struct
 */
class SDL_DollarGestureEvent extends __SDL_AbstractEvent
{
    public readonly int $timestamp;
    public readonly int $touchId;
    public readonly int $gestureId;
    public readonly int $numFingers;
    public readonly float $error;
    public readonly float $x;
    public readonly float $y;
}

/**
 * @internal This is autocompletion stub class for FFI struct
 */
class SDL_DropEvent extends __SDL_AbstractEvent
{
    public readonly int $timestamp;
    public readonly string $file;
    public readonly int $windowID;
}

/**
 * @internal This is autocompletion stub class for FFI struct
 */
class SDL_SensorEvent extends __SDL_AbstractEvent
{
    public readonly int $timestamp;
    public readonly int $which;
    /** @var array{float, float, float, float, float, float} */
    public readonly array $data;
}

/**
 * @internal This is autocompletion stub class for FFI struct
 */
class SDL_QuitEvent extends __SDL_AbstractEvent
{
    public readonly int $timestamp;
}

/**
 * @internal This is autocompletion stub class for FFI struct
 */
class SDL_OSEvent extends __SDL_AbstractEvent
{
    public readonly int $timestamp;
}

/**
 * @internal This is autocompletion stub class for FFI struct
 */
class SDL_UserEvent extends __SDL_AbstractEvent
{
    public readonly int $timestamp;
    public readonly int $windowID;
    public readonly int $code;
    public readonly CData $data1;
    public readonly CData $data2;
}

class SDL_SysWMmsg extends CData
{
}

/**
 * @internal This is autocompletion stub class for FFI struct
 */
class SDL_SysWMEvent extends __SDL_AbstractEvent
{
    public readonly int $timestamp;
    public readonly SDL_SysWMmsg $msg;
}

/**
 * @internal This is autocompletion stub class for FFI struct
 */
class SDL_Event extends __SDL_AbstractEvent
{
    /**
     * Common event data.
     */
    public readonly SDL_CommonEvent $common;

    /**
     * Display event data.
     */
    public readonly SDL_DisplayEvent $display;

    /**
     * Window event data.
     */
    public readonly SDL_WindowEvent $window;

    /**
     * Keyboard event data.
     */
    public readonly SDL_KeyboardEvent $key;

    /**
     * Text editing event data.
     */
    public readonly SDL_TextEditingEvent $edit;

    /**
     * Extended text editing event data.
     */
    public readonly SDL_TextEditingExtEvent $editExt;

    /**
     * Text input event data.
     */
    public readonly SDL_TextInputEvent $text;

    /**
     * Mouse motion event data.
     */
    public readonly SDL_MouseMotionEvent $motion;

    /**
     * Mouse button event data.
     */
    public readonly SDL_MouseButtonEvent $button;

    /**
     * Mouse wheel event data.
     */
    public readonly SDL_MouseWheelEvent $wheel;

    /**
     * Joystick axis event data.
     */
    public readonly SDL_JoyAxisEvent $jaxis;

    /**
     * Joystick ball event data.
     */
    public readonly SDL_JoyBallEvent $jball;

    /**
     * Joystick hat event data.
     */
    public readonly SDL_JoyHatEvent $jhat;

    /**
     * Joystick button event data.
     */
    public readonly SDL_JoyButtonEvent $jbutton;

    /**
     * Joystick device change event data.
     */
    public readonly SDL_JoyDeviceEvent $jdevice;

    /**
     * Joystick battery event data.
     */
    public readonly SDL_JoyBatteryEvent $jbattery;

    /**
     * Game Controller axis event data.
     */
    public readonly SDL_ControllerAxisEvent $caxis;

    /**
     * Game Controller button event data.
     */
    public readonly SDL_ControllerButtonEvent $cbutton;

    /**
     * Game Controller device event data.
     */
    public readonly SDL_ControllerDeviceEvent $cdevice;

    /**
     * Game Controller touchpad event data.
     */
    public readonly SDL_ControllerTouchpadEvent $ctouchpad;

    /**
     * Game Controller sensor event data.
     */
    public readonly SDL_ControllerSensorEvent $csensor;

    /**
     * Engine device event data.
     */
    public readonly SDL_AudioDeviceEvent $adevice;

    /**
     * Sensor event data.
     */
    public readonly SDL_SensorEvent $sensor;

    /**
     * Quit request event data.
     */
    public readonly SDL_QuitEvent $quit;

    /**
     * Custom event data.
     */
    public readonly SDL_UserEvent $user;

    /**
     * System dependent window event data.
     */
    public readonly SDL_SysWMEvent $syswm;

    /**
     * Touch finger event data.
     */
    public readonly SDL_TouchFingerEvent $tfinger;

    /**
     * Gesture event data.
     */
    public readonly SDL_MultiGestureEvent $mgesture;

    /**
     * Gesture event data.
     */
    public readonly SDL_DollarGestureEvent $dgesture;

    /**
     * Drag and drop event data.
     */
    public readonly SDL_DropEvent $drop;
}

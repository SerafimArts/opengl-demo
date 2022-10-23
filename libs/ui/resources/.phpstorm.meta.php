<?php

namespace PHPSTORM_META {

    registerArgumentsSet('bic_ui_keyboard_modifier', \Bic\UI\Keyboard\Modifier::NONE
        | \Bic\UI\Keyboard\Modifier::LSHIFT
        | \Bic\UI\Keyboard\Modifier::RSHIFT
        | \Bic\UI\Keyboard\Modifier::LCTRL
        | \Bic\UI\Keyboard\Modifier::RCTRL
        | \Bic\UI\Keyboard\Modifier::LALT
        | \Bic\UI\Keyboard\Modifier::RALT
        | \Bic\UI\Keyboard\Modifier::LGUI
        | \Bic\UI\Keyboard\Modifier::RGUI
        | \Bic\UI\Keyboard\Modifier::NUM
        | \Bic\UI\Keyboard\Modifier::CAPS
        | \Bic\UI\Keyboard\Modifier::MODE
        | \Bic\UI\Keyboard\Modifier::SCROLL
        | \Bic\UI\Keyboard\Modifier::CTRL
        | \Bic\UI\Keyboard\Modifier::SHIFT
        | \Bic\UI\Keyboard\Modifier::ALT
        | \Bic\UI\Keyboard\Modifier::GUI
        | \Bic\UI\Keyboard\Modifier::RESERVED
    );

    expectedArguments(
        \Bic\UI\Keyboard\Event::__construct(),
        2,
        argumentsSet('bic_ui_keyboard_modifier'),
    );

    expectedArguments(
        \Bic\UI\Keyboard\KeyUpEvent::__construct(),
        2,
        argumentsSet('bic_ui_keyboard_modifier'),
    );

    expectedArguments(
        \Bic\UI\Keyboard\KeyDownEvent::__construct(),
        2,
        argumentsSet('bic_ui_keyboard_modifier'),
    );

}

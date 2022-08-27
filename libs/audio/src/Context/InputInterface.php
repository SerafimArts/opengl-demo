<?php

declare(strict_types=1);

namespace Bic\Audio\Context;

use Bic\Audio\Device\InputDeviceInterface;

/**
 * @template-extends ContextInterface<InputDeviceInterface>
 */
interface InputInterface extends ContextInterface
{
    /**
     * @return InputDeviceInterface
     */
    public function getDevice(): InputDeviceInterface;
}

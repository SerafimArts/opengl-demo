<?php

/**
 * This file is part of Bic Engine package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Bic\Lib\SDL2\Exception;

class SDL2Exception extends \RuntimeException
{
    /**
     * @param string $message
     * @param int $code
     * @param \Throwable|null $prev
     */
    public function __construct(string $message = '', int $code = 0, \Throwable $prev = null)
    {
        parent::__construct($message, $code, $prev);

        /** @psalm-suppress all */
        ['file' => $this->file, 'line' => $this->line] = $this->getTrace()[0];
    }
}

<?php

declare(strict_types=1);

namespace Bic\Foundation\Exception;

use SebastianBergmann\Environment\Console;

class ConsoleHandler implements HandlerInterface
{
    /**
     * @var Console
     */
    private readonly Console $env;

    /**
     * @var positive-int
     */
    private readonly int $width;

    /**
     * @var bool
     */
    private readonly bool $colors;

    public function __construct()
    {
        $this->env = new Console();

        $this->width = $this->env->getNumberOfColumns();
        $this->colors = $this->env->hasColorSupport();
    }

    /**
     * @param string|int $text
     * @param string $color
     * @return string
     */
    private function color(string|int $text, string $color): string
    {
        if ($this->colors) {
            return "$color$text\u{001b}[0m";
        }

        return (string)$text;
    }

    /**
     * @param string|int $text
     * @return string
     */
    private function green(string|int $text): string
    {
        return $this->color($text, "\u{001b}[32m");
    }

    /**
     * @param string|int $text
     * @return string
     */
    private function yellow(string|int $text): string
    {
        return $this->color($text, "\u{001b}[33m");
    }

    /**
     * @param non-empty-string $char
     * @return non-empty-string
     */
    private function line(string $char): string
    {
        return \str_repeat($char, $this->width);
    }

    /**
     * @param \Throwable $e
     * @param positive-int|0 $prefix
     * @return string
     */
    private function getFormattedMessage(\Throwable $e): string
    {
        $width = $this->width - 2;

        $lines = \wordwrap($e->getMessage(), $width, \PHP_EOL);
        $lines = \array_map($this->yellow(...), \explode(\PHP_EOL, $lines));

        return ' ' . \rtrim(\implode(\PHP_EOL . ' ', $lines), '.') . '.';
    }

    /**
     * @param \Throwable $e
     * @return non-empty-string
     */
    private function getFormattedPathname(\Throwable $e): string
    {
        return ' in ' . $this->green($e->getFile()) . ':' . $this->green($e->getLine());
    }

    /**
     * @param \Throwable $e
     * @return non-empty-string
     */
    private function getFormattedTrace(\Throwable $e): string
    {
        $result = [];
        $length = \count($e->getTrace());

        foreach ($e->getTrace() as $i => $item) {
            if (!isset($item['file'], $item['line'])) {
                continue;
            }

            $index = $length - $i - 1;
            $result[] = "    #$index " . $this->green($item['file']) . ':' . $this->green($item['line']);
        }

        return \implode(\PHP_EOL, $result);
    }

    /**
     * {@inheritDoc}
     */
    public function throw(\Throwable $e): int
    {
        $message = \PHP_EOL
            . $this->line('─') . \PHP_EOL . \PHP_EOL
            . $this->getFormattedMessage($e) . \PHP_EOL . \PHP_EOL
            . $this->getFormattedPathname($e) . \PHP_EOL
            . $this->getFormattedTrace($e) . \PHP_EOL . \PHP_EOL
            . $this->line('─') . \PHP_EOL
        ;

        \fwrite(\STDOUT, $message);

        return $e->getCode() ?: self::ERROR_CODE;
    }
}

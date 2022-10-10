<?php

declare(strict_types=1);

namespace Local\Binaries;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;

final class Plugin implements PluginInterface
{
    private const VENDOR  = 'SerafimArts';
    private const PACKAGE = 'opengl-demo';
    private const VERSION = '0.0.1';

    /**
     * @param non-empty-string $name
     *
     * @return non-empty-string
     */
    private function urlTo(string $name): string
    {
        return \vsprintf('https://github.com/%s/%s/releases/download/%s/%s', [
            self::VENDOR,
            self::PACKAGE,
            self::VERSION,
            $name
        ]);
    }

    /**
     * @return non-empty-string
     */
    private function package(): string
    {
        return match (\PHP_OS_FAMILY) {
            'Windows' => \PHP_INT_SIZE === 8 ? 'binaries-windows-amd64.zip' : 'binaries-windows-x86.zip',
            'Linux', 'BSD' => 'binaries-linux-amd64.zip',
            'Darwin' => throw new \LogicException('Could not download MacOS binaries, please install it manually'),
        };
    }

    private function getInputPathname(): string
    {
        return $this->urlTo($this->getOutputFilename());
    }

    private function getBinDirectory(Composer $composer): string
    {
        $config = $composer->getConfig();

        return $config->get('bin-dir');
    }

    private function getOutputPathname(Composer $composer): string
    {
        return $this->getBinDirectory($composer) . '/'
             . $this->getOutputFilename();
    }

    private function getOutputFilename(): string
    {
        return $this->package();
    }

    private function getOutputDirectory(Composer $composer): string
    {
        return $this->getBinDirectory($composer);
    }

    public function activate(Composer $composer, IOInterface $io): void
    {
        try {
            $this->download($composer, $io);
            $this->extract($composer, $io);
        } catch (\Throwable $e) {
            $io->error($e->getMessage());
        }
    }

    private function download(Composer $composer, IOInterface $io): void
    {
        $input = $this->getInputPathname();
        $output = $this->getOutputPathname($composer);

        if (\is_file($output)) {
            return;
        }

        $io->write('<info>- Downloading:</info> ' . $input);

        \copy($input, $output);
    }

    private function extract(Composer $composer, IOInterface $io): void
    {
        $phar = new \PharData($this->getOutputPathname($composer));
        $output = $this->getOutputDirectory($composer);

        /** @var \PharFileInfo $file */
        foreach ($phar as $file) {
            if (\is_file($output . '/' . $file->getFilename())) {
                continue;
            }

            $io->write('<info>- Extracting:</info> ' . $file->getFilename() . ' (v' . self::VERSION . ')');
            $phar->extractTo($output, $file->getFilename());
        }
    }

    public function deactivate(Composer $composer, IOInterface $io): void
    {
    }

    public function uninstall(Composer $composer, IOInterface $io): void
    {
    }
}

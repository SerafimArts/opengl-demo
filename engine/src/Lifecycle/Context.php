<?php

/**
 * This file is part of Bic Engine package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Serafim\Bic\Lifecycle;

use Doctrine\Common\Annotations\Reader;
use FFI\CData;
use Serafim\Bic\Application;
use Serafim\Bic\EventLoop\LoopInterface;
use Serafim\Bic\Lifecycle\Annotation\Annotation;
use Serafim\Bic\Lifecycle\Annotation\OnEvent;
use Serafim\Bic\Lifecycle\Annotation\OnHide;
use Serafim\Bic\Lifecycle\Annotation\OnKeyDown;
use Serafim\Bic\Lifecycle\Annotation\OnKeyUp;
use Serafim\Bic\Lifecycle\Annotation\OnLoad;
use Serafim\Bic\Lifecycle\Annotation\OnMouseMove;
use Serafim\Bic\Lifecycle\Annotation\OnPause;
use Serafim\Bic\Lifecycle\Annotation\OnRender;
use Serafim\Bic\Lifecycle\Annotation\OnResume;
use Serafim\Bic\Lifecycle\Annotation\OnShow;
use Serafim\Bic\Lifecycle\Annotation\OnUnload;
use Serafim\Bic\Lifecycle\Annotation\OnUpdate;
use Serafim\SDL\Event;

/**
 * Class Context
 */
class Context
{
    /**
     * @var int
     */
    public const TYPE_UPDATE = 0x00;

    /**
     * @var int
     */
    public const TYPE_RENDER = 0x01;

    /**
     * @var int
     */
    public const TYPE_SHOW = 0x02;

    /**
     * @var int
     */
    public const TYPE_HIDE = 0x03;

    /**
     * @var int
     */
    public const TYPE_PAUSE = 0x04;

    /**
     * @var int
     */
    public const TYPE_RESUME = 0x05;

    /**
     * @var int
     */
    public const TYPE_LOAD = 0x06;

    /**
     * @var int
     */
    public const TYPE_UNLOAD = 0x07;

    /**
     * @var array|\Closure[][]
     */
    private array $callbacks = [
        self::TYPE_UPDATE => [],
        self::TYPE_RENDER => [],
        self::TYPE_SHOW   => [],
        self::TYPE_HIDE   => [],
        self::TYPE_PAUSE  => [],
        self::TYPE_RESUME => [],
        self::TYPE_LOAD   => [],
        self::TYPE_UNLOAD => [],
    ];

    /**
     * @var array|string[]
     */
    private array $mappings = [
        OnUpdate::class => self::TYPE_UPDATE,
        OnRender::class => self::TYPE_RENDER,
        OnShow::class   => self::TYPE_SHOW,
        OnHide::class   => self::TYPE_HIDE,
        OnPause::class  => self::TYPE_PAUSE,
        OnResume::class => self::TYPE_RESUME,
        OnLoad::class   => self::TYPE_LOAD,
        OnUnload::class => self::TYPE_UNLOAD,
    ];

    /**
     * @var array
     */
    private array $events = [];

    /**
     * @var Application
     */
    private Application $app;

    /**
     * Context constructor.
     *
     * @param Application $app
     * @param Reader $reader
     * @param object $context
     */
    public function __construct(Application $app, Reader $reader, object $context)
    {
        $this->app = $app;

        /**
         * @var \ReflectionMethod $ref
         * @var Annotation $annotation
         */
        foreach ($this->annotations($reader, $context) as $ref => $annotation) {
            $method = $ref->getClosure($context);

            if ($annotation instanceof OnEvent) {
                $this->events[] = [$annotation, $method];

                continue;
            }

            $this->callbacks[$this->mappings[\get_class($annotation)]][] = $method;
        }

        $this->load();
    }

    /**
     * @param Reader $reader
     * @param object $context
     * @return iterable
     */
    private function annotations(Reader $reader, object $context): iterable
    {
        $class = new \ReflectionObject($context);

        foreach ($class->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
            foreach ($reader->getMethodAnnotations($method) as $annotation) {
                if ($annotation instanceof Annotation) {
                    yield $method => $annotation;
                }
            }
        }
    }

    /**
     * @return void
     */
    private function load(): void
    {
        while (\count($this->callbacks[self::TYPE_LOAD]) > 0) {
            $handler = \array_pop($this->callbacks[self::TYPE_LOAD]);

            $this->app->call($handler);
        }
    }

    /**
     * @return void
     */
    public function unload(): void
    {
        while (\count($this->callbacks[self::TYPE_UNLOAD]) > 0) {
            $handler = \array_pop($this->callbacks[self::TYPE_UNLOAD]);

            $this->app->call($handler);
        }
    }

    /**
     * @param float $delta
     * @return void
     */
    public function update(float $delta): void
    {
        foreach ($this->callbacks[self::TYPE_UPDATE] as $callback) {
            $callback($delta);
        }
    }

    /**
     * @param float $delta
     * @return void
     */
    public function render(float $delta): void
    {
        foreach ($this->callbacks[self::TYPE_RENDER] as $callback) {
            $callback($delta);
        }
    }

    /**
     * @param CData|Event $event
     * @return void
     */
    public function event(CData $event): void
    {
        /**
         * @var OnEvent $annotation
         * @var \Closure $callback
         */
        foreach ($this->events as [$annotation, $callback]) {
            $handle = $this->handle($annotation, $event);

            if ($handle !== null) {
                $this->app->call($callback, ['event' => $handle, CData::class => $handle]);
            }
        }
    }

    /**
     * @param Annotation $annotation
     * @param CData|Event $event
     * @return CData|null
     */
    private function handle(Annotation $annotation, CData $event): ?CData
    {
        if ($annotation->type !== null && $annotation->type !== $event->type) {
            return null;
        }

        switch (\get_class($annotation)) {
            // Keyboard
            case OnKeyDown::class:
            case OnKeyUp::class:
                /** @var $annotation OnKeyDown */
                if ($annotation->code === null || $annotation->code === $event->key->keysym->scancode) {
                    return $event->key;
                }

                return null;

            // Mouse
            case OnMouseMove::class:
                return $event->motion;

            default:
                return $event;
        }
    }

    /**
     * @return void
     */
    public function show(): void
    {
        foreach ($this->callbacks[self::TYPE_SHOW] as $callback) {
            $this->app->call($callback);
        }
    }

    /**
     * @return void
     */
    public function hide(): void
    {
        foreach ($this->callbacks[self::TYPE_HIDE] as $callback) {
            $this->app->call($callback);
        }
    }

    /**
     * @return void
     */
    public function pause(): void
    {
        foreach ($this->callbacks[self::TYPE_PAUSE] as $callback) {
            $this->app->call($callback);
        }
    }

    /**
     * @return void
     */
    public function resume(): void
    {
        foreach ($this->callbacks[self::TYPE_PAUSE] as $callback) {
            $this->app->call($callback);
        }
    }

    /**
     * @param int $type
     * @param \Closure $method
     * @param Annotation $annotation
     * @return void
     */
    private function register(int $type, \Closure $method, Annotation $annotation): void
    {
        $this->callbacks[$type][] = [$annotation, $method];
    }
}

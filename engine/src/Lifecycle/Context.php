<?php

declare(strict_types=1);

namespace Serafim\Bic\Lifecycle;

use FFI\CData;
use Serafim\Bic\Application;
use Serafim\Bic\Lifecycle\Annotation\LifecycleAttribute;
use Serafim\Bic\Lifecycle\Annotation\OnEvent;
use Serafim\Bic\Lifecycle\Annotation\OnHide;
use Serafim\Bic\Lifecycle\Annotation\OnKeyDown;
use Serafim\Bic\Lifecycle\Annotation\OnKeyUp;
use Serafim\Bic\Lifecycle\Annotation\OnLoad;
use Serafim\Bic\Lifecycle\Annotation\OnMouseMove;
use Serafim\Bic\Lifecycle\Annotation\OnMouseWheel;
use Serafim\Bic\Lifecycle\Annotation\OnPause;
use Serafim\Bic\Lifecycle\Annotation\OnRender;
use Serafim\Bic\Lifecycle\Annotation\OnResume;
use Serafim\Bic\Lifecycle\Annotation\OnShow;
use Serafim\Bic\Lifecycle\Annotation\OnUnload;
use Serafim\Bic\Lifecycle\Annotation\OnUpdate;
use Serafim\SDL\Event;

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
     * @param Application $app
     * @param object $context
     */
    public function __construct(Application $app, object $context)
    {
        $this->app = $app;

        /**
         * @var \ReflectionMethod $ref
         * @var Annotation $annotation
         */
        foreach ($this->attributes($context) as $ref => $annotation) {
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
     * @param object $context
     * @return iterable<LifecycleAttribute>
     */
    private function attributes(object $context): iterable
    {
        $class = new \ReflectionObject($context);

        foreach ($class->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
            $attributes = $method->getAttributes(LifecycleAttribute::class, \ReflectionAttribute::IS_INSTANCEOF);

            foreach ($attributes as $attribute) {
                yield $method => $attribute->newInstance();
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
         * @var OnEvent $attribute
         * @var \Closure $callback
         */
        foreach ($this->events as [$attribute, $callback]) {
            $handle = $this->handle($attribute, $event);

            if ($handle !== null) {
                $this->app->call($callback, ['event' => $handle, CData::class => $handle]);
            }
        }
    }

    /**
     * @param LifecycleAttribute $attr
     * @param CData|Event $event
     *
     * @return CData|null
     */
    private function handle(LifecycleAttribute $attr, CData $event): ?CData
    {
        if ($attr->type !== $event->type) {
            return null;
        }

        switch (\get_class($attr)) {
            // Keyboard
            case OnKeyDown::class:
            case OnKeyUp::class:
                /** @var $attr OnKeyDown */
                if ($attr->key === null || $attr->key->getId() === $event->key->keysym->scancode) {
                    return $event->key;
                }

                return null;

            // Mouse
            case OnMouseMove::class:
                return $event->motion;

            case OnMouseWheel::class:
                return $event->wheel;

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
}

<?php

declare(strict_types=1);

namespace Serafim\Bic\Lifecycle;

use FFI\CData;
use Illuminate\Contracts\Container\BindingResolutionException;
use Serafim\Bic\Application;
use Serafim\Bic\EventLoop\LoopInterface;
use Serafim\Bic\EventLoop\OrderedEventLoop;
use Serafim\Bic\EventLoop\WorkerInterface;
use Serafim\Bic\Renderer\RendererInterface;
use Serafim\Bic\Renderer\Viewport\ViewportInterface;
use Serafim\Bic\Window\WindowInterface;
use Serafim\SDL\Event;
use Serafim\SDL\Kernel\Event\Type;
use Serafim\SDL\Kernel\Video\WindowEvent;

abstract class Lifecycle implements WorkerInterface
{
    /**
     * @var WindowInterface
     */
    public WindowInterface $window;

    /**
     * @var RendererInterface
     */
    public RendererInterface $renderer;

    /**
     * @var ViewportInterface
     */
    public ViewportInterface $viewport;

    /**
     * @var LoopInterface
     */
    public LoopInterface $loop;

    /**
     * @var Application
     */
    public Application $app;

    /**
     * @var object|null
     */
    protected ?object $controller = null;

    /**
     * @var Context|null
     */
    protected ?Context $context = null;

    /**
     * @param Application $app
     * @throws BindingResolutionException
     */
    public function __construct(Application $app)
    {
        $app->instance(self::class, $this);
        $app->instance(static::class, $this);

        $this->app = $app;

        $this->window = $app->make(WindowInterface::class);
        $this->renderer = $app->make(RendererInterface::class);
        $this->viewport = $app->make(ViewportInterface::class);
        $this->loop = $app->make(LoopInterface::class);

        $this->loop->use($this);
    }

    /**
     * @param float $delta
     * @return void
     */
    public function onUpdate(float $delta): void
    {
        if ($this->context !== null) {
            $this->context->update($delta);
        }
    }

    /**
     * @param float $delta
     * @return void
     */
    public function onRender(float $delta): void
    {
        $this->renderer->clear();

        if ($this->context !== null) {
            $this->context->render($delta);
        }

        $this->renderer->present();
    }

    /**
     * @param CData|Event $event
     * @return void
     */
    public function onEvent(CData $event): void
    {
        $this->defaultEventLogic($event);

        if ($this->context !== null) {
            $this->context->event($event);
        }
    }

    /**
     * @param CData|Event $event
     * @return void
     */
    protected function defaultEventLogic(CData $event): void
    {
        if ($event->type === Type::SDL_WINDOWEVENT) {
            switch ($event->window->event) {
                case WindowEvent::SDL_WINDOWEVENT_FOCUS_LOST:
                    $this->loop->pause();
                    break;

                case WindowEvent::SDL_WINDOWEVENT_FOCUS_GAINED:
                    $this->loop->resume();
                    break;
            }
        }
    }

    /**
     * @return void
     */
    public function onPause(): void
    {
        if ($this->context !== null) {
            $this->context->pause();
        }
    }

    /**
     * @return void
     */
    public function onResume(): void
    {
        if ($this->context !== null) {
            $this->context->resume();
        }
    }

    /**
     * @param string $controller
     * @param array $arguments
     * @return void
     * @throws BindingResolutionException
     */
    public function show(string $controller, array $arguments = []): void
    {
        if ($this->context !== null) {
            $this->context->hide();
        }

        foreach ($arguments as $name => $argument) {
            if (\class_exists($name) || \interface_exists($name)) {
                $this->app->instance($name, $argument);
            }
        }

        $this->controller = $this->app->make($controller);
        $this->context = $this->app->make(Context::class, [
            'context' => $this->controller,
        ]);

        $this->context->show();
    }

    /**
     * @return void
     * @throws BindingResolutionException
     */
    public function run(): void
    {
        $this->window->show();

        $this->app->run();
    }
}

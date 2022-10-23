<?php

declare(strict_types=1);

namespace Bic\UI\SDL;

use Bic\UI\FactoryInterface;
use Bic\UI\Event;
use Bic\UI\Keyboard\Key;
use Bic\UI\Keyboard\UserKey;
use Bic\UI\ManagerInterface;
use Bic\UI\Mouse\Button;
use Bic\UI\Mouse\UserButton;
use Bic\UI\Mouse\Wheel;
use Bic\UI\SDL\Internal\HandlerFactory;
use Bic\UI\SDL\Internal\WindowInstanceInfo;
use Bic\UI\SDL\Kernel\EventType as SDLEventType;
use Bic\UI\SDL\Kernel\MouseButton as SDLButton;
use Bic\UI\SDL\Kernel\SysWMType;
use Bic\UI\SDL\Kernel\WindowEvent as SDLWindowEvent;
use Bic\UI\SDL\Kernel\WindowFlags;
use Bic\UI\SDL\Kernel\WindowPosition;
use Bic\UI\Window\CreateInfo;
use Bic\UI\Window\Handle\AppleHandle;
use Bic\UI\Window\Handle\WaylandHandle;
use Bic\UI\Window\Handle\Win32Handle;
use Bic\UI\Window\Handle\XLibHandle;
use Bic\UI\Window\HandleInterface;
use Bic\UI\Window\Mode;
use Bic\UI\Window\WindowInterface;
use FFI\CData;
use Bic\UI\Mouse\Event as MouseEvent;
use Bic\UI\Window\Event as WindowEvent;
use Bic\UI\Keyboard\Event as KeyEvent;

/**
 * @template-implements FactoryInterface<Window, CreateInfo>
 * @template-implements ManagerInterface<Window>
 * @template-implements \IteratorAggregate<array-key, Window>
 */
final class Factory implements FactoryInterface, ManagerInterface, \IteratorAggregate
{
    /**
     * @var int
     */
    private const DEFAULT_FLAGS = WindowFlags::SDL_WINDOW_OPENGL
        | WindowFlags::SDL_WINDOW_MOUSE_CAPTURE
        | WindowFlags::SDL_WINDOW_INPUT_FOCUS
        | WindowFlags::SDL_WINDOW_SHOWN
        // | WindowFlags::SDL_WINDOW_INPUT_GRABBED
    ;

    /**
     * @var array<int, WindowInstanceInfo>
     */
    private array $windows;

    /**
     * @param object $sdl
     */
    public function __construct(
        private readonly object $sdl,
    ) {
    }

    /**
     * @psalm-taint-sink file $library
     *
     * @param non-empty-string|null $library
     *
     * @return static
     */
    public static function fromLibrary(string $library = null): self
    {
        return new self(new Library($library));
    }

    /**
     * {@inheritDoc}
     */
    public function run(): void
    {
        $pointer = \FFI::addr(
            $event = $this->sdl->new('SDL_Event')
        );

        if (\Fiber::getCurrent()) {
            while ($this->windows !== []) {
                while ($this->sdl->SDL_PollEvent($pointer)) {
                    \Fiber::suspend($this->process($event));
                }

                \Fiber::suspend(); // NOP
            }
        } else {
            while ($this->windows !== []) {
                while ($this->sdl->SDL_PollEvent($pointer)) {
                    $this->process($event);
                }
            }
        }
    }

    /**
     * @param CData $event
     * @return Event|null
     */
    private function process(CData $event): ?Event
    {
        return match ($event->type) {
            SDLEventType::SDL_WINDOWEVENT => $this->processWindowEvent($event->window),
            SDLEventType::SDL_MOUSEWHEEL => $this->processMouseWheelEvent($event->wheel),
            SDLEventType::SDL_MOUSEMOTION => $this->processMouseMotionEvent($event->motion),
            SDLEventType::SDL_MOUSEBUTTONDOWN,
            SDLEventType::SDL_MOUSEBUTTONUP => $this->processMouseButtonEvent($event->button),
            SDLEventType::SDL_KEYDOWN,
            SDLEventType::SDL_KEYUP => $this->processKeyEvent($event->key),
            default => null,
        };
    }

    /**
     * @param CData $event
     * @return KeyEvent|null
     */
    private function processKeyEvent(CData $event): ?KeyEvent
    {
        // Skip repetitions
        if ($event->repeat) {
            return null;
        }

        $window = $this->windows[$event->windowID]->window;

        if ($event->repeat || ($info = $this->windows[$event->windowID] ?? null) === null) {
            return null;
        }

        $key = Key::tryFrom($event->keysym->scancode)
            ?? UserKey::create($event->keysym->scancode);

        if ($event->type === SDLEventType::SDL_KEYDOWN) {
            return new KeyEvent\KeyDownEvent($info->window, $key);
        }

        return new KeyEvent\KeyUpEvent($info->window, $key);
    }

    private function processMouseButtonEvent(CData $event): ?MouseEvent\MouseButtonEvent
    {
        if (($info = $this->windows[$event->windowID] ?? null) === null) {
            return null;
        }

        $button = match ($event->button) {
            SDLButton::SDL_BUTTON_LEFT => Button::LEFT,
            SDLButton::SDL_BUTTON_MIDDLE => Button::MIDDLE,
            SDLButton::SDL_BUTTON_RIGHT => Button::RIGHT,
            SDLButton::SDL_BUTTON_X1 => Button::X1,
            SDLButton::SDL_BUTTON_X2 => Button::X2,
            default => UserButton::create($event->which),
        };

        if ($event->type === SDLEventType::SDL_MOUSEBUTTONDOWN) {
            return new MouseEvent\MouseDownEvent($info->window, $event->x, $event->y, $button);
        }

        return new MouseEvent\MouseUpEvent($info->window, $event->x, $event->y, $button);
    }

    private function processMouseMotionEvent(CData $event): ?MouseEvent\MouseMoveEvent
    {
        if (($info = $this->windows[$event->windowID] ?? null) === null) {
            return null;
        }

        return new MouseEvent\MouseMoveEvent($info->window, $event->x, $event->y);
    }

    private function processMouseWheelEvent(CData $event): ?MouseEvent\MouseWheelEvent
    {
        if (($info = $this->windows[$event->windowID] ?? null) === null) {
            return null;
        }

        return match (true) {
            $event->y > 0 => new MouseEvent\MouseWheelEvent($info->window, Wheel::UP),
            $event->y < 0 => new MouseEvent\MouseWheelEvent($info->window, Wheel::DOWN),

            default => $event->x > 0
                ? new MouseEvent\MouseWheelEvent($info->window, Wheel::RIGHT)
                : new MouseEvent\MouseWheelEvent($info->window, Wheel::LEFT),
        };
    }

    private function processWindowEvent(CData $event): ?WindowEvent
    {
        if (!isset($this->windows[$event->windowID])) {
            return null;
        }

        $info = $this->windows[$event->windowID];

        if ($info->closable && $event->event === SDLWindowEvent::SDL_WINDOWEVENT_CLOSE) {
            try {
                return new WindowEvent\WindowCloseEvent($info->window);
            } finally {
                $info->window->close();
            }
        }

        if ($event->event === SDLWindowEvent::SDL_WINDOWEVENT_SIZE_CHANGED) {
            $size = $info->window->getSize();
            $size->width = $event->data1;
            $size->height = $event->data2;

            return null;
        }

        if ($event->event === SDLWindowEvent::SDL_WINDOWEVENT_MOVED) {
            $position = $info->window->getPosition();
            $position->x = $event->data1;
            $position->y = $event->data2;

            return null;
        }

        return match ($event->event) {
            SDLWindowEvent::SDL_WINDOWEVENT_HIDDEN,
            SDLWindowEvent::SDL_WINDOWEVENT_MINIMIZED => new WindowEvent\WindowHideEvent($info->window),
            SDLWindowEvent::SDL_WINDOWEVENT_SHOWN,
            SDLWindowEvent::SDL_WINDOWEVENT_RESTORED => new WindowEvent\WindowShowEvent($info->window),
            SDLWindowEvent::SDL_WINDOWEVENT_FOCUS_LOST => new WindowEvent\WindowBlurEvent($info->window),
            SDLWindowEvent::SDL_WINDOWEVENT_FOCUS_GAINED => new WindowEvent\WindowFocusEvent($info->window),
            default => null,
        };
    }

    /**
     * @param CreateInfo $info
     *
     * @return Window
     */
    protected function instance(CreateInfo $info): Window
    {
        $x = $info->position?->x ?? WindowPosition::SDL_WINDOWPOS_CENTERED;
        $y = $info->position?->y ?? WindowPosition::SDL_WINDOWPOS_CENTERED;

        $flags = self::DEFAULT_FLAGS | match ($info->mode) {
            Mode::DESKTOP_FULLSCREEN => WindowFlags::SDL_WINDOW_FULLSCREEN_DESKTOP,
            Mode::FULLSCREEN => WindowFlags::SDL_WINDOW_FULLSCREEN,
            default => 0,
        };

        $ptr = $this->sdl->SDL_CreateWindow($info->title, $x, $y, $info->size->width, $info->size->height, $flags);

        $instance = new Window($this->sdl, $ptr, $info, $this->detach(...));

        if ($info->mode === Mode::HIDDEN) {
            $instance->hide();
        }

        return $instance;
    }

    /**
     * {@inheritDoc}
     */
    public function create(CreateInfo $info = new CreateInfo()): WindowInterface
    {
        $instance = $this->instance($info);

        $instanceInfo = new WindowInstanceInfo(
            window: $instance,
            id: $this->sdl->SDL_GetWindowID($instance->getCData()),
            closable: $info->closable,
        );

        $this->windows[$instanceInfo->id] = $instanceInfo;

        return $instance;
    }

    /**
     * {@inheritDoc}
     */
    public function detach(WindowInterface $window): void
    {
        foreach ($this->windows as $id => $info) {
            if ($info->window === $window) {
                unset($this->windows[$id]);
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator(): \Traversable
    {
        foreach ($this->windows as $window => $info) {
            yield $window;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function count(): int
    {
        return $this->windows->count();
    }
}

<?php

declare(strict_types=1);

namespace Bic\UI\SDL;

use Bic\UI\Keyboard\Event as KeyEvent;
use Bic\UI\Keyboard\Key;
use Bic\UI\Keyboard\KeyInterface;
use Bic\UI\Keyboard\UserKey;
use Bic\UI\Mouse\Button;
use Bic\UI\Mouse\ButtonInterface;
use Bic\UI\Mouse\Event as MouseEvent;
use Bic\UI\Mouse\UserButton;
use Bic\UI\Mouse\Wheel;
use Bic\UI\Position;
use Bic\UI\SDL\Kernel\EventType as SDLEventType;
use Bic\UI\SDL\Kernel\MouseButton as SDLButton;
use Bic\UI\SDL\Kernel\WindowEvent as SDLWindowEvent;
use Bic\UI\Size;
use Bic\UI\Window\CreateInfo;
use Bic\UI\Window\Event as WindowEvent;
use Bic\UI\Window\HandleInterface;
use Bic\UI\Window\WindowInterface;
use FFI\CData;
use JetBrains\PhpStorm\Immutable;

/**
 * @psalm-suppress all
 *
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Bic\UI\SDL
 */
final class Window implements WindowInterface
{
    /**
     * The title of the window, in UTF-8 encoding.
     *
     * @var string
     */
    public readonly string $title;

    /**
     * The size of the window, in screen coordinates.
     *
     * @var Size
     */
    public readonly Size $size;

    /**
     * The X (left) and Y (top) position of the window.
     *
     * @var Position
     */
    public readonly Position $position;

    /**
     * @var bool
     * @readonly
     * @psalm-readonly-allow-private-mutation
     */
    #[Immutable(allowedWriteScope: Immutable::PRIVATE_WRITE_SCOPE)]
    public bool $closed = false;

    /**
     * @var bool
     */
    private readonly bool $closable;

    /**
     * @param object $sdl
     * @param CData $ptr
     * @param HandleInterface $handle
     * @param CreateInfo $info
     * @param \Closure($this):void $close
     */
    public function __construct(
        private readonly object $sdl,
        private readonly CData $ptr,
        public readonly HandleInterface $handle,
        CreateInfo $info,
        private readonly \Closure $close
    ) {
        [$x, $y] = [$this->sdl->new('int'), $this->sdl->new('int')];

        $this->sdl->SDL_GetWindowPosition($this->ptr, \FFI::addr($x), \FFI::addr($y));

        $this->title = $info->title;
        $this->size = new Size($info->size->width, $info->size->height);
        $this->position = new Position($x->cdata, $y->cdata);
        $this->closable = $info->closable;
    }

    /**
     * {@inheritDoc}
     */
    public function getHandle(): HandleInterface
    {
        return $this->handle;
    }

    /**
     * {@inheritDoc}
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * {@inheritDoc}
     */
    public function getSize(): Size
    {
        return $this->size;
    }

    /**
     * {@inheritDoc}
     */
    public function getPosition(): Position
    {
        return $this->position;
    }

    /**
     * {@inheritDoc}
     */
    public function isClosed(): bool
    {
        return $this->closed;
    }

    /**
     * @return CData
     */
    public function getCData(): CData
    {
        return $this->ptr;
    }

    /**
     * {@inheritDoc}
     */
    public function show(): void
    {
        if ($this->closed) {
            throw new \LogicException('Window already has been closed');
        }

        $this->sdl->SDL_ShowWindow($this->ptr);
    }

    /**
     * {@inheritDoc}
     */
    public function hide(): void
    {
        if ($this->closed) {
            throw new \LogicException('Window already has been closed');
        }

        $this->sdl->SDL_HideWindow($this->ptr);
    }

    /**
     * {@inheritDoc}
     */
    public function close(): void
    {
        if ($this->closed === false) {
            $this->closed = true;
            ($this->close)($this);
            $this->sdl->SDL_DestroyWindow($this->ptr);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function run(): void
    {
        $event = $this->sdl->new('SDL_Event');
        $pointer = \FFI::addr($event);

        if (\Fiber::getCurrent()) {
            while ($this->closed === false) {
                if ($this->sdl->SDL_PollEvent($pointer)) {
                    \Fiber::suspend($this->process($event));
                } else {
                    \Fiber::suspend(); // NOP
                }
            }

            \Fiber::suspend(new WindowEvent\WindowCloseEvent($this));
        }

        while ($this->closed === false) {
            while ($this->sdl->SDL_PollEvent($pointer)) {
                $windowShouldClose = $this->closable === true
                    && $event->type === SDLEventType::SDL_WINDOWEVENT
                    && $event->window->event === SDLWindowEvent::SDL_WINDOWEVENT_CLOSE;

                if ($windowShouldClose) {
                    $this->close();
                }
            }
        }
    }

    private function process(CData $ev): ?object
    {
        return match ($ev->type) {
            SDLEventType::SDL_WINDOWEVENT =>
                match ($ev->window->event) {
                    SDLWindowEvent::SDL_WINDOWEVENT_CLOSE => $this->closable ? $this->close() : null,
                    SDLWindowEvent::SDL_WINDOWEVENT_HIDDEN,
                    SDLWindowEvent::SDL_WINDOWEVENT_MINIMIZED => new WindowEvent\WindowHideEvent($this),
                    SDLWindowEvent::SDL_WINDOWEVENT_SHOWN,
                    SDLWindowEvent::SDL_WINDOWEVENT_RESTORED => new WindowEvent\WindowShowEvent($this),
                    SDLWindowEvent::SDL_WINDOWEVENT_FOCUS_LOST => new WindowEvent\WindowBlurEvent($this),
                    SDLWindowEvent::SDL_WINDOWEVENT_FOCUS_GAINED => new WindowEvent\WindowFocusEvent($this),
                    default => null,
                },
            SDLEventType::SDL_MOUSEWHEEL => match (true) {
                $ev->wheel->y > 0 => new MouseEvent\MouseWheelEvent($this, Wheel::UP),
                $ev->wheel->y < 0 => new MouseEvent\MouseWheelEvent($this, Wheel::DOWN),
                default => $ev->wheel->x > 0
                    ? new MouseEvent\MouseWheelEvent($this, Wheel::RIGHT)
                    : new MouseEvent\MouseWheelEvent($this, Wheel::LEFT),
            },
            SDLEventType::SDL_MOUSEMOTION => new MouseEvent\MouseMoveEvent($this, $ev->motion->x, $ev->motion->y),
            SDLEventType::SDL_MOUSEBUTTONDOWN => new MouseEvent\MouseDownEvent(
                $this,
                $ev->button->x,
                $ev->button->y,
                self::getMouseButton($ev),
            ),
            SDLEventType::SDL_MOUSEBUTTONUP => new MouseEvent\MouseUpEvent(
                $this,
                $ev->button->x,
                $ev->button->y,
                self::getMouseButton($ev),
            ),
            SDLEventType::SDL_KEYDOWN => $ev->key->repeat ? null : new KeyEvent\KeyDownEvent($this, self::getKeyboardKey($ev)),
            SDLEventType::SDL_KEYUP => new KeyEvent\KeyUpEvent($this, self::getKeyboardKey($ev)),
            default => null,
        };
    }

    private static function getKeyboardKey(CData $ev): KeyInterface
    {
        return Key::tryFrom($ev->key->keysym->scancode)
            ?? UserKey::create($ev->key->keysym->scancode);
    }

    private static function getMouseButton(CData $ev): ButtonInterface
    {
        return match ($ev->button->button) {
            SDLButton::SDL_BUTTON_LEFT => Button::LEFT,
            SDLButton::SDL_BUTTON_MIDDLE => Button::MIDDLE,
            SDLButton::SDL_BUTTON_RIGHT => Button::RIGHT,
            SDLButton::SDL_BUTTON_X1 => Button::X1,
            SDLButton::SDL_BUTTON_X2 => Button::X2,
            default => UserButton::create($ev->button->which),
        };
    }
}

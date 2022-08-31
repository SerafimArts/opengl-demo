<?php

declare(strict_types=1);

namespace Bic\UI\SDL2;

use Bic\Lib\SDL2;
use Bic\Lib\SDL2\Image;
use Bic\UI\EventInterface;
use Bic\UI\Keyboard\Event\KeyDownEvent;
use Bic\UI\Keyboard\Event\KeyUpEvent;
use Bic\UI\Mouse\Event\MouseDownEvent;
use Bic\UI\Mouse\Event\MouseMoveEvent;
use Bic\UI\Mouse\Event\MouseUpEvent;
use Bic\UI\Mouse\Event\MouseWheelEvent;
use Bic\UI\Window\Event\WindowBlurEvent;
use Bic\UI\Window\Event\WindowCloseEvent;
use Bic\UI\Window\Event\WindowFocusEvent;
use Bic\UI\Window\Event\WindowHideEvent;
use Bic\UI\Window\Event\WindowMovedEvent;
use Bic\UI\Window\Event\WindowResizedEvent;
use Bic\UI\Window\Event\WindowShowEvent;
use Bic\UI\Window\Window as BaseWindow;
use Bic\UI\Window\WindowInterface;
use FFI\CData;

/**
 * @package ui-sdl2
 */
final class Window extends BaseWindow
{
    /**
     * @var CData|null
     */
    private ?CData $icon = null;

    /**
     * @var CData
     */
    private readonly CData $event;

    /**
     * @var CData
     */
    private readonly CData $eventPointer;

    /**
     * @param SDL2 $sdl2
     * @param Image $image
     * @param CData $ptr
     * @psalm-param \Closure(WindowInterface):void $detach
     * @param \Closure $detach
     */
    public function __construct(
        private readonly SDL2 $sdl2,
        private readonly SDL2\Image $image,
        public readonly CData $ptr,
        \Closure $detach,
    ) {
        $this->event = $this->sdl2->new('SDL_Event');
        $this->eventPointer = \FFI::addr($this->event);

        parent::__construct($detach);
    }

    /**
     * {@inheritDoc}
     */
    public function setIcon(string $pathname): void
    {
        if ($this->icon !== null) {
            $this->sdl2->SDL_FreeSurface($this->icon);
        }

        $this->icon = $this->sdl2->cast('SDL_Surface*', $this->image->IMG_Load($pathname));

        $this->sdl2->SDL_SetWindowIcon($this->ptr, $this->icon);
    }

    /**
     * {@inheritDoc}
     */
    public function close(): void
    {
        if (!$this->isClosed()) {
            parent::close();

            $this->sdl2->SDL_DestroyWindow($this->ptr);
        }
    }

    /**
     * @return EventInterface|null
     *
     * @psalm-suppress MixedArgument
     * @psalm-suppress MixedPropertyFetch
     * @psalm-suppress UndefinedPropertyFetch
     */
    public function poll(): ?EventInterface
    {
        if ($this->isClosed()) {
            return null;
        }

        if ($this->sdl2->SDL_PollEvent($this->eventPointer) > 0) {
            return match ($this->event->type) {
                SDL2\EventType::SDL_QUIT => new WindowCloseEvent($this),
                SDL2\EventType::SDL_WINDOWEVENT => match ($this->event->window->event) {
                    SDL2\WindowEventID::SDL_WINDOWEVENT_MINIMIZED => new WindowHideEvent($this),
                    SDL2\WindowEventID::SDL_WINDOWEVENT_RESTORED => new WindowShowEvent($this),
                    SDL2\WindowEventID::SDL_WINDOWEVENT_FOCUS_LOST => new WindowBlurEvent($this),
                    SDL2\WindowEventID::SDL_WINDOWEVENT_FOCUS_GAINED => new WindowFocusEvent($this),
                    SDL2\WindowEventID::SDL_WINDOWEVENT_MOVED => new WindowMovedEvent(
                        $this,
                        $this->event->window->data1,
                        $this->event->window->data2,
                    ),
                    SDL2\WindowEventID::SDL_WINDOWEVENT_SIZE_CHANGED => new WindowResizedEvent(
                        $this,
                        $this->event->window->data1,
                        $this->event->window->data2,
                    ),
                    default => null,
                },
                SDL2\EventType::SDL_KEYDOWN => $this->event->key->repeat === 0
                    ? new KeyDownEvent(
                        $this,
                        Mapping::key($this->event->key->keysym->scancode),
                        Mapping::mod($this->event->key->keysym->mod),
                    ) : null,
                SDL2\EventType::SDL_KEYUP => $this->event->key->repeat === 0
                    ? new KeyUpEvent(
                        $this,
                        Mapping::key($this->event->key->keysym->scancode),
                        Mapping::mod($this->event->key->keysym->mod),
                    ) : null,
                SDL2\EventType::SDL_MOUSEMOTION => new MouseMoveEvent(
                    $this,
                    $this->event->motion->x,
                    $this->event->motion->y,
                ),
                SDL2\EventType::SDL_MOUSEBUTTONDOWN => new MouseDownEvent(
                    $this,
                    $this->event->button->x,
                    $this->event->button->y,
                    Mapping::button($this->event->button->button),
                ),
                SDL2\EventType::SDL_MOUSEBUTTONUP => new MouseUpEvent(
                    $this,
                    $this->event->button->x,
                    $this->event->button->y,
                    Mapping::button($this->event->button->button),
                ),
                SDL2\EventType::SDL_MOUSEWHEEL => new MouseWheelEvent(
                    $this,
                    Mapping::wheel($this->event->wheel->x, $this->event->wheel->y),
                ),
                default => null,
            };
        }

        return null;
    }
}

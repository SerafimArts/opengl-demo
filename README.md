# PHP OpenGL (SDL) Demo

Please note that this is only a demo and may contain non-optimal, crazy and 
completely unbelievable programming techniques.

If your psyche was injured please consult a doctor.

Peace <3

------

Oh yes, according to my information, nobody has ever done such things 
in **pure PHP**.

![](https://habrastorage.org/webt/xd/8u/dn/xd8udncjdbysbj4dglahj8kfizw.png)

## Requiremens

- PHP 7.4+
- ext-ffi
- [Composer](https://getcomposer.org/download/)
- SDL2 (only MacOS and Linux)
- SDL2 Image (only MacOS and Linux)

## Installation

### Windows

- `composer install`
- `copy .env.example .env`
- `php app.php`

### Linux

- `sudo apt install libsdl2-2.0-0 -y`
- `sudo apt install libsdl2-image-2.0-0 -y`
- `composer install`
- `cp .env.example .env`
- `php app.php`

### MacOS

- `brew install sdl2`
- `brew install sdl2_image`
- `composer install`
- `cp .env.example .env`
- `php app.php`

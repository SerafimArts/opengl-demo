# PHP OpenGL (SDL) Demo

Please note that this is only a demo and may contain non-optimal, crazy and 
completely unbelievable programming techniques.

If your psyche was injured please consult a doctor.

Peace <3

------

Oh yes, according to my information, nobody has ever done such things 
in **pure PHP**.

![](https://habrastorage.org/webt/xd/8u/dn/xd8udncjdbysbj4dglahj8kfizw.png)

Demo in action: https://www.youtube.com/watch?v=vsBbJbhKeeU

## Requirements

- PHP 7.4+
- ext-ffi
- [Composer](https://getcomposer.org/download/)

### Additional Requirements (MacOS and Linux only)

- SDL 2.0+
- SDL Image 2.0+
- SDL TTF 2.0+

## Installation

### Windows

- `composer install`
- `php app.php`

### Linux

- `sudo apt install libsdl2-2.0-0 -y`
- `sudo apt install libsdl2-image-2.0-0 -y`
- `sudo apt install libsdl2-ttf-2.0-0 -y`
- `composer install`
- `php app.php`

### Linux / Docker

- `docker run -it -v /tmp/.X11-unix:/tmp/.X11-unix -e DISPLAY=$DISPLAY -e XAUTHORITY=$XAUTHORITY $(docker build -q .)`

### MacOS

- `brew install sdl2`
- `brew install sdl2_image`
- `brew install sdl2_ttf`
- `composer install`
- `php app.php`


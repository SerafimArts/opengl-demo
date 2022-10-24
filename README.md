# PHP OpenGL (SDL) Demo

<p align="center">
    <a href="https://packagist.org/packages/serafim/opengl-demo"><img src="https://poser.pugx.org/serafim/opengl-demo/require/php?style=for-the-badge" alt="PHP 8.1+"></a>
    <a href="https://packagist.org/packages/serafim/opengl-demo"><img src="https://poser.pugx.org/serafim/opengl-demo/version?style=for-the-badge" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/serafim/opengl-demo"><img src="https://poser.pugx.org/serafim/opengl-demo/v/unstable?style=for-the-badge" alt="Latest Unstable Version"></a>
    <a href="https://raw.githubusercontent.com/serafim/opengl-demo/master/LICENSE.md"><img src="https://poser.pugx.org/serafim/opengl-demo/license?style=for-the-badge" alt="License MIT"></a>
</p>

<p align="center">
    <a href="https://github.com/serafim/opengl-demo/actions"><img src="https://github.com/serafim/opengl-demo/workflows/build/badge.svg"></a>
</p>

Please note that this is only a demo and may contain non-optimal, crazy and
completely unbelievable programming techniques (Well, besides the fact that this 
code is written by a PHP 🐒 developer who has never encountered game development).

If your psyche was injured please consult a doctor.

Peace <3

------

Oh yes, according to my information, nobody has ever done such things 
in **pure PHP**.

![](https://habrastorage.org/webt/xd/8u/dn/xd8udncjdbysbj4dglahj8kfizw.png)

Demo in action: https://www.youtube.com/watch?v=vsBbJbhKeeU

## Requirements

- PHP 8.1+
- ext-ffi
- [Composer](https://getcomposer.org/download/)
- Windows, Linux or Unix (over X11 and Wayland), MacOS

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

### MacOS

- `brew install sdl2`
- `brew install sdl2_image`
- `brew install sdl2_ttf`
- `composer install`
- `php app.php`

### Docker

- `docker run -it -v /tmp/.X11-unix:/tmp/.X11-unix -e DISPLAY=$DISPLAY -e XAUTHORITY=$XAUTHORITY $(docker build -q .)`

## General Information

- The "`./engine/`" directory contains deprecated code that will be removed in
  the future (for now it is required for the demo to work). All actual code is
  currently contained in the `./libs/` directory and some part of the old code
  has already been replaced with the current one.
- It is planned to move away from separate installation of binaries 
  (SDL, SDL Image, etc...) and ship them together with the application.
  The `./libs/binaries-downloader` package is responsible for their installation. 
  Assemblies are available here in the "assets" section here: https://github.com/SerafimArts/opengl-demo/releases/tag/0.0.1
- It is planned to move away from SDL support and switch to native OpenGL API 
  (This is one of the reasons why MacOS can disappear from the list of available
  operating systems), and then Vulkan API.
- After rewriting the graphics pipeline, it is planned to add a sound/audio 
  engine (fuck knows how to do it).

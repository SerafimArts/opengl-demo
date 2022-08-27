<?php

declare(strict_types=1);

namespace Bic\Audio\Channel;

enum Channels: int
{
    case MONO = 1;
    case STEREO = 2;
    case QUAD = 4;
    case CINEMA51 = 6;
    case CINEMA71 = 8;
}

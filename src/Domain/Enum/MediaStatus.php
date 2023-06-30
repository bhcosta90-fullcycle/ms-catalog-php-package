<?php

namespace BRCas\MV\Domain\Enum;

enum MediaStatus: int
{
    case PROCESSING = 0;
    case COMPLETE = 1;
    case PENDING = 2;
}

<?php

declare(strict_types=1);

namespace BRCas\MV\Domain\Enum;

enum CastMemberType: int
{
    case ACTOR = 2;
    case DIRECTOR = 1;
}

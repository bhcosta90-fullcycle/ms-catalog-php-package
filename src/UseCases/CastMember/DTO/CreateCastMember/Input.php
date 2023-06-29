<?php

declare(strict_types=1);

namespace BRCas\MV\UseCases\CastMember\DTO\CreateCastMember;

class Input
{
    public function __construct(
        public string $name,
        public int $type,
        public bool $isActive = true,
    ) {
        //
    }
}

<?php

declare(strict_types=1);

namespace BRCas\MV\UseCases\CastMember\DTO\UpdateCastMember;

class Input
{
    public function __construct(
        public string $id,
        public string $name,
        public ?string $description = null,
        public bool $isActive = true,
    ) {
        //
    }
}

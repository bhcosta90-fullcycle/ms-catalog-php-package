<?php

declare(strict_types=1);

namespace BRCas\MV\UseCases\Category\DTO\CreateCategory;

class Input
{
    public function __construct(
        public string $name,
        public ?string $description = null,
        public bool $isActive = true,
    ) {
        //
    }
}

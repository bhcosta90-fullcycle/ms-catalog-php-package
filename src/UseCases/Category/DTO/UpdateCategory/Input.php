<?php

declare(strict_types=1);

namespace BRCas\MV\UseCases\Category\DTO\UpdateCategory;

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

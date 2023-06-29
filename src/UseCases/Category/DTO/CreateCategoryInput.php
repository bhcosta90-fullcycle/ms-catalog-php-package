<?php

declare(strict_types=1);

namespace Core\UseCases\Category\DTO;

class CreateCategoryInput
{
    public function __construct(
        public string $name,
        public ?string $description = null,
        public bool $isActive = true,
    ) {
        //
    }
}

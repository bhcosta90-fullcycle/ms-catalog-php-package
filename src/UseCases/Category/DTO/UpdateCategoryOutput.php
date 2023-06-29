<?php

declare(strict_types=1);

namespace Core\UseCases\Category\DTO;

class UpdateCategoryOutput
{
    public function __construct(
        public string $id,
        public string $name,
        public ?string $description = null,
        public bool $is_active = true,
    ) {
        //
    }
}

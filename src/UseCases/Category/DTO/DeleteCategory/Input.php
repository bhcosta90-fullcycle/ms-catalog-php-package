<?php

declare(strict_types=1);

namespace Core\UseCases\Category\DTO\DeleteCategory;

class Input
{
    public function __construct(
        public string $id,
    ) {
        //
    }
}

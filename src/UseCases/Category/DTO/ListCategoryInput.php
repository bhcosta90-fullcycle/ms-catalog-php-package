<?php

declare(strict_types=1);

namespace Core\UseCases\Category\DTO;

class ListCategoryInput
{
    public function __construct(
        public string $id,
    ) {
        //
    }
}

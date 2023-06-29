<?php

declare(strict_types=1);

namespace Core\UseCases\Category\DTO;

class DeleteCategoryOutput
{
    public function __construct(
        public bool $success,
    ) {
        //
    }
}

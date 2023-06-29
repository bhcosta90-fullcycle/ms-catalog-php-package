<?php

declare(strict_types=1);

namespace Core\UseCases\Category\DTO\DeleteCategory;

class Output
{
    public function __construct(
        public bool $success,
    ) {
        //
    }
}

<?php

declare(strict_types=1);

namespace BRCas\MV\UseCases\Category\DTO\DeleteCategory;

class Input
{
    public function __construct(
        public string $id,
    ) {
        //
    }
}

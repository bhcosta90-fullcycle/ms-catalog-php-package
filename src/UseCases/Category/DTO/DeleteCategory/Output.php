<?php

declare(strict_types=1);

namespace BRCas\MV\UseCases\Category\DTO\DeleteCategory;

class Output
{
    public function __construct(
        public bool $success,
    ) {
        //
    }
}

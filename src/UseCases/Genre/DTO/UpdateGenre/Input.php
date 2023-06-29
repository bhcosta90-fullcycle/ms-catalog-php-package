<?php

declare(strict_types=1);

namespace BRCas\MV\UseCases\Genre\DTO\UpdateGenre;

class Input
{
    public function __construct(
        public string $id,
        public string $name,
        public array $categories = [],
        public bool $isActive = true,
    ) {
        //
    }
}

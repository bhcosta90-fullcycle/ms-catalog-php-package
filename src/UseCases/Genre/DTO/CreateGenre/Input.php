<?php

declare(strict_types=1);

namespace BRCas\MV\UseCases\Genre\DTO\CreateGenre;

class Input
{
    public function __construct(
        public string $name,
        public array $categories = [],
        public bool $isActive = true,
    ) {
        //
    }
}

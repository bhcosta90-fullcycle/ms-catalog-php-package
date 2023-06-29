<?php

declare(strict_types=1);

namespace BRCas\MV\UseCases\Genre\DTO;

class GenreOutput
{
    public function __construct(
        public string $id,
        public string $name,
        public array $categories,
        public bool $is_active = true,
        public string $created_at = '',
    ) {
        //
    }
}

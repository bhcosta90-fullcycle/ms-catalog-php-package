<?php

declare(strict_types=1);

namespace BRCas\MV\UseCases\Genre\DTO;

class GenreInput
{
    public function __construct(
        public string $id,
    ) {
        //
    }
}

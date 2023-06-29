<?php

declare(strict_types=1);

namespace BRCas\MV\UseCases\Genre\DTO\DeleteGenre;

class Output
{
    public function __construct(
        public bool $success,
    ) {
        //
    }
}

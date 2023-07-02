<?php

declare(strict_types=1);

namespace BRCas\MV\UseCases\Video\DTO;

class ListVideoInput
{
    public function __construct(
        public string $id,
    ) {
        //
    }
}

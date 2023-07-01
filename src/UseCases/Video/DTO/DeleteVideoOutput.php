<?php

declare(strict_types=1);

namespace BRCas\MV\UseCases\Video\DTO;

class DeleteVideoOutput
{
    public function __construct(
        public bool $success,
    ) {
        //
    }
}

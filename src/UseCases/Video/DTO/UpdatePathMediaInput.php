<?php

declare(strict_types=1);

namespace BRCas\MV\UseCases\Video\DTO;

class UpdatePathMediaInput
{
    public function __construct(
        public string $id,
        public string $path,
        public string $type,
    ) {
        //
    }
}

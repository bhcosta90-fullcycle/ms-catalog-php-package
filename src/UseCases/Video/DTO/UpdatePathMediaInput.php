<?php

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

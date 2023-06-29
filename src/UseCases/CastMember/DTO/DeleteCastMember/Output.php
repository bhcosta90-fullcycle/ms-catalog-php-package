<?php

declare(strict_types=1);

namespace BRCas\MV\UseCases\CastMember\DTO\DeleteCastMember;

class Output
{
    public function __construct(
        public bool $success,
    ) {
        //
    }
}

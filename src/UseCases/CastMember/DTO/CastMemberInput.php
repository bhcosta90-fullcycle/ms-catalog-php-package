<?php

declare(strict_types=1);

namespace BRCas\MV\UseCases\CastMember\DTO;

class CastMemberInput
{
    public function __construct(
        public string $id,
    ) {
        //
    }
}

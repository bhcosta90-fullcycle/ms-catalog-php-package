<?php

declare(strict_types=1);

namespace BRCas\MV\UseCases\CastMember\DTO;

class CastMemberOutput
{
    public function __construct(
        public string $id,
        public string $name,
        public int $type,
        public bool $is_active = true,
        public string $created_at = '',
    ) {
        //
    }
}

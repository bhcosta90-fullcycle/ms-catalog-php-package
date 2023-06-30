<?php

declare(strict_types=1);

namespace BRCas\MV\Domain\ValueObject;

use BRCas\MV\Domain\Enum\MediaStatus;

class Media
{
    public function __construct(
        protected string $path,
        protected MediaStatus $status,
        protected ?string $encoded = null,
    ) {
        //
    }

    public function __get($property)
    {
        return $this->{$property};
    }
}

<?php

declare(strict_types=1);

namespace BRCas\MV\Domain\ValueObject;

class Image
{
    public function __construct(protected string $image)
    {
        //
    }

    public function path(): string
    {
        return $this->image;
    }
}

<?php

namespace BRCas\MV\Domain\Event\DTO;

use BRCas\CA\Domain\Events\DTO\PayloadEventOutputInterface;

class VideoCreateEvent implements PayloadEventOutputInterface
{
    public function __construct(
        public string $id,
        public ?string $pathVideo,
        public ?string $pathTrailer,
    ) {
        //
    }
}

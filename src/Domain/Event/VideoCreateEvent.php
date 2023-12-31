<?php

declare(strict_types=1);

namespace BRCas\MV\Domain\Event;

use BRCas\CA\Domain\Events\DTO\PayloadEventOutputInterface;
use BRCas\CA\Domain\Events\EventInterface;
use BRCas\MV\Domain\Entity\Video;

class VideoCreateEvent implements EventInterface
{
    public function __construct(
        protected Video $video,
    ) {
    }
    public function name(): string
    {
        return 'video.create.' . $this->video->id();
    }

    public function payload(): PayloadEventOutputInterface
    {
        return new DTO\VideoCreateEvent(
            id: $this->video->id(),
            pathVideo: $this->video->videoFile()?->path,
            pathTrailer: $this->video->trailerFile()?->path,
        );
    }
}

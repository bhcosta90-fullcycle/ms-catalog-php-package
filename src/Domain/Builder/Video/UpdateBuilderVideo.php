<?php

namespace BRCas\MV\Domain\Builder\Video;

use BRCas\CA\Domain\ValueObject\Uuid;
use BRCas\MV\Domain\Entity\Video;
use BRCas\MV\Domain\Enum\Rating;
use DateTime;

class UpdateBuilderVideo extends CreateBuilderVideo
{
    public function createEntity(object $input): Video
    {
        $this->entity = new Video(
            id: new Uuid($input->id),
            title: $input->title,
            description: $input->description,
            yearLaunched: $input->yearLaunched,
            duration: $input->duration,
            opened: $input->opened,
            rating: Rating::from($input->rating),
            createdAt: new DateTime($input->created_at),
        );

        $this->addIds($input);

        return $this->entity;
    }
}

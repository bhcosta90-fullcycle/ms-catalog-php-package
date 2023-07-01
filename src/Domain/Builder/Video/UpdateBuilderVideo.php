<?php

namespace BRCas\MV\Domain\Builder\Video;

use BRCas\CA\Domain\Abstracts\EntityAbstract;
use BRCas\CA\Domain\ValueObject\Uuid;
use BRCas\MV\Domain\Entity\Video;
use BRCas\MV\Domain\Enum\Rating;
use DateTime;

class UpdateBuilderVideo extends CreateBuilderVideo
{
    public function createEntity(object $entity): Video
    {
        $this->entity = $entity;
        return $this->entity;
    }
}

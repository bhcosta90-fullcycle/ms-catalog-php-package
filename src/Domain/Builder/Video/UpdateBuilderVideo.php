<?php

namespace BRCas\MV\Domain\Builder\Video;

use BRCas\MV\Domain\Entity\Video;

class UpdateBuilderVideo extends CreateBuilderVideo
{
    public function createEntity(object $entity): Video
    {
        $this->entity = $entity;
        return $this->entity;
    }
}

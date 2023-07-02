<?php

namespace BRCas\MV\UseCases\Video\Interfaces;

use BRCas\CA\UseCase\EventManagerInterface;
use BRCas\MV\Domain\Entity\Video;

interface VideoEventManagerInterface extends EventManagerInterface
{
    public function updateMedia(Video $video): Video;
}

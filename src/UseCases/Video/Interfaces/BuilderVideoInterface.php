<?php

namespace BRCas\MV\UseCases\Video\Interfaces;

use BRCas\MV\Domain\Entity\Video;
use BRCas\MV\Domain\Enum\MediaStatus;

interface BuilderVideoInterface
{
    public function createEntity(object $input): Video;

    public function addMediaVideo($filePath, MediaStatus $status): self;

    public function addMediaTrailer($filePath, MediaStatus $status): self;

    public function addImageThumb($filePath): self;

    public function addImageThumbHalf($filePath): self;

    public function addImageBanner($filePath): self;

    public function getEntity(): Video;
}

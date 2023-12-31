<?php

declare(strict_types=1);

namespace BRCas\MV\Domain\Builder\Video;

use BRCas\MV\Domain\Entity\Video;
use BRCas\MV\Domain\Enum\MediaStatus;

interface BuilderVideoInterface
{
    public function createEntity(object $input): Video;

    public function addIds(object $input);

    public function addMediaVideo($filePath, MediaStatus $status, ?string $encoded = null): self;

    public function addMediaTrailer($filePath, MediaStatus $status, ?string $encoded = null): self;

    public function addImageThumb($filePath): self;

    public function addImageHalf($filePath): self;

    public function addImageBanner($filePath): self;

    public function getEntity(): Video;
}

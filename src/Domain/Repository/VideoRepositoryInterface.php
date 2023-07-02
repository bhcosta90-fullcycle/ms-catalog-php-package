<?php

declare(strict_types=1);

namespace BRCas\MV\Domain\Repository;

use BRCas\CA\Repository\RepositoryInterface;
use BRCas\MV\Domain\Entity\Video;

interface VideoRepositoryInterface extends RepositoryInterface
{
    public function updateMedia(Video $video): Video;
}

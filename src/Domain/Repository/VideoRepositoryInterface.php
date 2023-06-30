<?php

declare(strict_types=1);

namespace BRCas\MV\Domain\Repository;

use BRCas\CA\Repository\ItemInterface;
use BRCas\CA\Repository\PaginateInterface;
use BRCas\MV\Domain\Entity\Video;

interface VideoRepositoryInterface
{
    public function insert(Video $video): Video;

    public function all(): ItemInterface;

    public function paginate(): PaginateInterface;

    public function getById(string $id): Video;

    public function update(Video $video): Video;

    public function delete(Video $video): bool;

    public function updateMedia(Video $video): Video;
}

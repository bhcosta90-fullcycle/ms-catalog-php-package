<?php

declare(strict_types=1);

namespace BRCas\MV\Domain\Repository;

use BRCas\CA\Repository\KeyValueInterface;
use BRCas\CA\Repository\RepositoryInterface;

interface CategoryRepositoryInterface extends RepositoryInterface
{
    public function getIdsByListId(array $categories = []): KeyValueInterface;
}
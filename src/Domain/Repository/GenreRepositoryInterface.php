<?php

declare(strict_types=1);

namespace BRCas\MV\Domain\Repository;

use BRCas\CA\Repository\ItemInterface;
use BRCas\CA\Repository\PaginateInterface;
use BRCas\MV\Domain\Entity\Genre;

interface GenreRepositoryInterface
{
    public function insert(Genre $category): Genre;
    
    public function all(): ItemInterface;

    public function paginate(): PaginateInterface;

    public function getById(string $id): Genre;

    public function update(Genre $category): Genre;

    public function delete(Genre $category): bool;
}
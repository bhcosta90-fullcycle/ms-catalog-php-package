<?php

declare(strict_types=1);

namespace Core\Domain\Repository;

use BRCas\CA\Repository\ItemInterface;
use BRCas\CA\Repository\PaginateInterface;
use Core\Domain\Entity\Category;

interface CategoryRepositoryInterface
{
    public function insert(Category $category): Category;
    
    public function all(): ItemInterface;

    public function paginate(): PaginateInterface;

    public function getById(string $id): Category;

    public function update(Category $category): Category;

    public function delete(Category $category): bool;
}
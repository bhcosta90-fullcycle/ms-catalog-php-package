<?php

declare(strict_types=1);

namespace BRCas\CA\Repository;

use BRCas\CA\Domain\Abstracts\EntityAbstract;

interface RepositoryInterface
{

    public function insert(EntityAbstract $entity): EntityAbstract;

    public function all(): ItemInterface;

    public function paginate(): PaginateInterface;

    public function getById(string $id): EntityAbstract;

    public function update(EntityAbstract $entity): EntityAbstract;

    public function delete(EntityAbstract $entity): bool;
}

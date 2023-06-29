<?php

declare(strict_types=1);

namespace BRCas\MV\Domain\Repository;

use BRCas\CA\Repository\ItemInterface;
use BRCas\CA\Repository\KeyValueInterface;
use BRCas\CA\Repository\PaginateInterface;
use BRCas\MV\Domain\Entity\CastMember;

interface CastMemberRepositoryInterface
{
    public function insert(CastMember $category): CastMember;
    
    public function all(): ItemInterface;

    public function paginate(): PaginateInterface;

    public function getById(string $id): CastMember;

    public function update(CastMember $category): CastMember;

    public function delete(CastMember $category): bool;

    public function getIdsByListId(array $categories = []): KeyValueInterface;
}
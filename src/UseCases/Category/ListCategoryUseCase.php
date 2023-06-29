<?php

declare(strict_types=1);

namespace BRCas\MV\UseCases\Category;

use BRCas\MV\Domain\Repository\CategoryRepositoryInterface;

class ListCategoryUseCase
{
    public function __construct(
        protected CategoryRepositoryInterface $repository,
    ) {
        //
    }

    public function execute(DTO\ListCategory\Input $input): DTO\ListCategory\Output
    {
        $domain = $this->repository->getById($input->id);
        return new DTO\ListCategory\Output(
            id: $domain->id(),
            name: $domain->name,
            is_active: $domain->isActive,
            created_at: $domain->createdAt(),
        );
    }
}

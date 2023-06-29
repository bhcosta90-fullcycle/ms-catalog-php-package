<?php

declare(strict_types=1);

namespace BRCas\MV\UseCases\Category;

use BRCas\MV\Domain\Repository\CategoryRepositoryInterface;

class ListCategoriesUseCase
{
    public function __construct(
        protected CategoryRepositoryInterface $repository,
    ) {
        //
    }

    public function execute(DTO\ListCategories\Input $input): DTO\ListCategories\Output
    {
        $domains = $this->repository->paginate();

        return new DTO\ListCategories\Output(
            items: $domains->items(),
            total: $domains->total(),
            last_page: $domains->lastPage(),
            first_page: $domains->firstPage(),
            current_page: $domains->currentPage(),
            per_page: $domains->perPage(),
            to: $domains->to(),
            from: $domains->from(),
        );
    }
}

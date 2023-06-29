<?php

declare(strict_types=1);

namespace BRCas\MV\UseCases\Genre;

use BRCas\MV\Domain\Repository\GenreRepositoryInterface;

class ListGenresUseCase
{
    public function __construct(
        protected GenreRepositoryInterface $repository,
    ) {
        //
    }

    public function execute(DTO\ListGenres\Input $input): DTO\ListGenres\Output
    {
        $domains = $this->repository->paginate();

        return new DTO\ListGenres\Output(
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

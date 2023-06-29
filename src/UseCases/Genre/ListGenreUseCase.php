<?php

declare(strict_types=1);

namespace BRCas\MV\UseCases\Genre;

use BRCas\MV\Domain\Repository\GenreRepositoryInterface;

class ListGenreUseCase
{
    public function __construct(
        protected GenreRepositoryInterface $repository,
    ) {
        //
    }

    public function execute(DTO\GenreInput $input): DTO\GenreOutput
    {
        $domain = $this->repository->getById($input->id);
        return new DTO\GenreOutput(
            id: $domain->id(),
            name: $domain->name,
            is_active: $domain->isActive,
            created_at: $domain->createdAt(),
        );
    }
}

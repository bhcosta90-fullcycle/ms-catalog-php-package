<?php

declare(strict_types=1);

namespace BRCas\MV\UseCases\Genre;

use BRCas\MV\Domain\Repository\GenreRepositoryInterface;

class DeleteGenreUseCase
{
    public function __construct(
        protected GenreRepositoryInterface $repository,
    ) {
        //
    }

    public function execute(DTO\GenreInput $input): DTO\DeleteGenre\Output
    {
        $domain = $this->repository->getById($input->id);
        $success = $this->repository->delete($domain);

        return new DTO\DeleteGenre\Output(
            success: $success,
        );
    }
}

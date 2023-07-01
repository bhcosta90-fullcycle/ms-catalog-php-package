<?php

declare(strict_types=1);

namespace BRCas\MV\UseCases\Video;

use BRCas\CA\Repository\PaginateInterface;
use BRCas\MV\Domain\Repository\VideoRepositoryInterface;

class ListVideosUseCase
{
    public function __construct(
        protected VideoRepositoryInterface $repository,
    ) {
        //
    }

    public function execute(): PaginateInterface
    {
        return $this->repository->paginate();
    }
}

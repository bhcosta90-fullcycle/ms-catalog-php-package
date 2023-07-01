<?php

declare(strict_types=1);

namespace BRCas\MV\UseCases\Video;

use BRCas\MV\Domain\Repository\VideoRepositoryInterface;

class ListVideosUseCase
{
    public function __construct(
        protected VideoRepositoryInterface $repository,
    ) {
        //
    }

    public function execute(): DTO\ListVideosOutput
    {
        $domains = $this->repository->paginate();

        return new DTO\ListVideosOutput(
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

<?php

declare(strict_types=1);

namespace BRCas\MV\UseCases\CastMember;

use BRCas\MV\Domain\Repository\CastMemberRepositoryInterface;

class ListCastMembersUseCase
{
    public function __construct(
        protected CastMemberRepositoryInterface $repository,
    ) {
        //
    }

    public function execute(): DTO\ListCastMembers\Output
    {
        $domains = $this->repository->paginate();

        return new DTO\ListCastMembers\Output(
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

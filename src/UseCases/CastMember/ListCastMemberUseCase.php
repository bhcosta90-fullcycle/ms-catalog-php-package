<?php

declare(strict_types=1);

namespace BRCas\MV\UseCases\CastMember;

use BRCas\MV\Domain\Repository\CastMemberRepositoryInterface;

class ListCastMemberUseCase
{
    public function __construct(
        protected CastMemberRepositoryInterface $repository,
    ) {
        //
    }

    public function execute(DTO\CastMemberInput $input): DTO\CastMemberOutput
    {
        $domain = $this->repository->getById($input->id);
        return new DTO\CastMemberOutput(
            id: $domain->id(),
            name: $domain->name,
            type: $domain->type->value,
            is_active: $domain->isActive,
            created_at: $domain->createdAt(),
        );
    }
}

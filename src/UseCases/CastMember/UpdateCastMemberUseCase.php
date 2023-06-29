<?php

declare(strict_types=1);

namespace BRCas\MV\UseCases\CastMember;

use BRCas\MV\Domain\Repository\CastMemberRepositoryInterface;

class UpdateCastMemberUseCase
{
    public function __construct(
        protected CastMemberRepositoryInterface $repository,
    ) {
        //
    }

    public function execute(DTO\UpdateCastMember\Input $input): DTO\CastMemberOutput
    {
        $domain = $this->repository->getById($input->id);

        if ($input->isActive) {
            $domain->enable();
        } else {
            $domain->disable();
        }

        $domain->update(name: $input->name);

        $newDomain = $this->repository->update($domain);

        return new DTO\CastMemberOutput(
            id: $newDomain->id(),
            name: $newDomain->name,
            type: $newDomain->type->value,
            is_active: $newDomain->isActive,
            created_at: $newDomain->createdAt(),
        );
    }
}

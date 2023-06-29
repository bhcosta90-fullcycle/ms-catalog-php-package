<?php

declare(strict_types=1);

namespace BRCas\MV\UseCases\CastMember;

use BRCas\MV\Domain\Entity\CastMember;
use BRCas\MV\Domain\Enum\CastMemberType;
use BRCas\MV\Domain\Repository\CastMemberRepositoryInterface;

class CreateCastMemberUseCase
{
    public function __construct(
        protected CastMemberRepositoryInterface $repository,
    ) {
        //
    }

    public function execute(DTO\CreateCastMember\Input $input): DTO\CastMemberOutput
    {
        $domain = new CastMember(
            name: $input->name,
            type: CastMemberType::from($input->type),
            isActive: $input->isActive,
        );

        $newDomain = $this->repository->insert($domain);

        return new DTO\CastMemberOutput(
            id: $newDomain->id(),
            name: $newDomain->name,
            type: $newDomain->type->value,
            is_active: $newDomain->isActive,
            created_at: $newDomain->createdAt(),
        );
    }
}

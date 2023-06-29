<?php

declare(strict_types=1);

namespace BRCas\MV\UseCases\CastMember;

use BRCas\MV\Domain\Repository\CastMemberRepositoryInterface;

class DeleteCastMemberUseCase
{
    public function __construct(
        protected CastMemberRepositoryInterface $repository,
    ) {
        //
    }

    public function execute(DTO\CastMemberInput $input): DTO\DeleteCastMember\Output
    {
        $domain = $this->repository->getById($input->id);
        $success = $this->repository->delete($domain);

        return new DTO\DeleteCastMember\Output(
            success: $success,
        );
    }
}

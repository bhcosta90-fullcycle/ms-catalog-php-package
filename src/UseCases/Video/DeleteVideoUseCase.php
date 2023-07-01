<?php

declare(strict_types=1);

namespace BRCas\MV\UseCases\Video;

use BRCas\MV\Domain\Repository\VideoRepositoryInterface;

class DeleteVideoUseCase
{
    public function __construct(
        protected VideoRepositoryInterface $repository,
    ) {
        //
    }

    public function execute(DTO\ListVideoInput $input): DTO\DeleteVideoOutput
    {
        $domain = $this->repository->getById($input->id);
        $success = $this->repository->delete($domain);

        return new DTO\DeleteVideoOutput(
            success: $success,
        );
    }
}

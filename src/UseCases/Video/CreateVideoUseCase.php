<?php

namespace BRCas\MV\UseCases\Video;

use BRCas\CA\UseCase\DatabaseTransactionInterface;
use BRCas\CA\UseCase\FileStorageInterface;
use BRCas\MV\Domain\Entity\Video;
use BRCas\MV\Domain\Enum\Rating;
use BRCas\MV\Domain\Event\VideoCreateEvent;
use BRCas\MV\Domain\Repository\VideoRepositoryInterface;
use Throwable;

class CreateVideoUseCase
{
    public function __construct(
        protected VideoRepositoryInterface $repository,
        protected DatabaseTransactionInterface $transaction,
        protected FileStorageInterface $storage,
        protected Interfaces\VideoEventManagerInterface $eventManager,
    ) {
        //
    }

    public function execute(DTO\CreateVideoInput $input): DTO\VideoOutput
    {
        $domain = $this->createEntity($input);

        try {
            $this->repository->insert($domain);

            if ($pathVideoFile = $this->storeMedia($domain->id(), $input->videoFile)) {
                $this->eventManager(new VideoCreateEvent($domain));
            }

            $this->transaction->commit();

            return new DTO\VideoOutput(
                id: $domain->id(),
                title: $domain->title,
                description: $domain->description,
                yearLaunched: $domain->yearLaunched,
                duration: $domain->duration,
                opened: $domain->opened,
                rating: $domain->rating->value,
                created_at: $domain->createdAt(),
            );

        } catch(Throwable $e) {
            $this->transaction->rollback();
            throw $e;
        }
    }

    protected function createEntity(DTO\CreateVideoInput $input): Video
    {
        $domain = new Video(
            title: $input->title,
            description: $input->description,
            yearLaunched: $input->yearLaunched,
            duration: $input->duration,
            opened: $input->opened,
            rating: Rating::from($input->rating)
        );

        foreach ($input->categories as $category) {
            $domain->addCategory($category);
        }

        foreach ($input->genres as $genres) {
            $domain->addGenre($genres);
        }

        foreach ($input->castMembers as $castMember) {
            $domain->addCastMember($castMember);
        }

        return $domain;
    }

    protected function storeMedia(string $path, ?array $media = null): ?string
    {
        if ($media) {
            return $this->storage->store(path: $path, file: $media);
        }

        return null;
    }
}

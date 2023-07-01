<?php

namespace BRCas\MV\Domain\Builder\Video;

use BRCas\MV\Domain\Entity\Video;
use BRCas\MV\Domain\Enum\MediaStatus;
use BRCas\MV\Domain\Enum\Rating;
use BRCas\MV\Domain\ValueObject\Image;
use BRCas\MV\Domain\ValueObject\Media;

class CreateBuilderVideo implements BuilderVideoInterface
{
    protected ?Video $entity = null;

    public function __construct()
    {
        $this->reset();
    }

    public function createEntity(object $input): Video
    {
        $this->entity = new Video(
            title: $input->title,
            description: $input->description,
            yearLaunched: $input->yearLaunched,
            duration: $input->duration,
            opened: $input->opened,
            rating: Rating::from($input->rating)
        );

        return $this->entity;
    }

    public function addIds(object $input) {
        foreach ($input->categories as $category) {
            $this->entity->addCategory($category);
        }

        foreach ($input->genres as $genres) {
            $this->entity->addGenre($genres);
        }

        foreach ($input->castMembers as $castMember) {
            $this->entity->addCastMember($castMember);
        }
    }

    public function addMediaVideo($path, MediaStatus $status, ?string $encoded = null): self
    {
        $media = new Media(path: $path, status: MediaStatus::PENDING, encoded: $encoded);
        $this->entity->setVideoFile($media);
        return $this;
    }

    public function addMediaTrailer($path, MediaStatus $status, ?string $encoded = null): self
    {
        $media = new Media(path: $path, status: MediaStatus::PENDING, encoded: $encoded);
        $this->entity->setTrailerFile($media);

        return $this;
    }

    public function addImageThumb($path): self
    {
        $this->entity->setThumbFile(new Image(image: $path));
        return $this;
    }

    public function addImageHalf($path): self
    {
        $this->entity->setThumbHalf(new Image(image: $path));
        return $this;
    }

    public function addImageBanner($path): self
    {
        $this->entity->setBannerFile(new Image(image: $path));
        return $this;
    }

    public function getEntity(): Video
    {
        return $this->entity;
    }

    protected function reset()
    {
        $this->entity = null;
    }
}

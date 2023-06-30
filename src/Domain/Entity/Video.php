<?php

declare(strict_types=1);

namespace BRCas\MV\Domain\Entity;

use BRCas\CA\Domain\Abstracts\EntityAbstract;
use BRCas\CA\Domain\Exceptions\ValidationNotificationException;
use BRCas\CA\Domain\ValueObject\Uuid;
use BRCas\MV\Domain\Enum\Rating;
use BRCas\MV\Domain\Factory\VideoValidatorFactory;
use BRCas\MV\Domain\ValueObject\Image;
use BRCas\MV\Domain\ValueObject\Media;
use DateTime;

class Video extends EntityAbstract
{
    public function __construct(
        protected string $title,
        protected string $description,
        protected int $yearLaunched,
        protected int $duration,
        protected bool $opened,
        protected Rating $rating,
        protected ?Image $thumbFile = null,
        protected ?Image $thumbHalf = null,
        protected ?Image $bannerFile = null,
        protected ?Media $trailerFile = null,
        protected ?Media $videoFile = null,
        protected array $categories = [],
        protected array $genres = [],
        protected array $castMembers = [],
        protected bool $publish = false,
        protected ?Uuid $id = null,
        protected ?DateTime $createdAt = null,
    ) {
        parent::__construct();

        $this->id = $this->id ?: Uuid::make();
        $this->createdAt = $this->createdAt ?: new DateTime();

        $this->validation();
    }

    public function addCategory(string $category)
    {
        array_push($this->categories, $category);
    }

    public function removeCategory(string $category)
    {
        if (array_search($category, $this->categories) !== false) {
            unset($this->categories[array_search($category, $this->categories)]);
            $this->categories = array_values($this->categories);
        }
    }

    public function addGenre(string $genre)
    {
        array_push($this->genres, $genre);
    }

    public function removeGenre(string $genre)
    {
        if (array_search($genre, $this->genres) !== false) {
            unset($this->genres[array_search($genre, $this->genres)]);
            $this->genres = array_values($this->genres);
        }
    }

    public function addCastMember(string $castMember)
    {
        array_push($this->castMembers, $castMember);
    }

    public function removeCastMember(string $castMember)
    {
        if (array_search($castMember, $this->castMembers) !== false) {
            unset($this->castMembers[array_search($castMember, $this->castMembers)]);
            $this->castMembers = array_values($this->castMembers);
        }
    }

    public function thumbFile(): ?Image
    {
        return $this->thumbFile;
    }

    public function thumbHalf(): ?Image
    {
        return $this->thumbHalf;
    }

    public function bannerFile(): ?Image
    {
        return $this->bannerFile;
    }

    public function trailerFile(): ?Media
    {
        return $this->trailerFile;
    }

    public function videoFile(): ?Media
    {
        return $this->videoFile;
    }

    protected function validation()
    {
        VideoValidatorFactory::make()->validate($this);

        if ($this->notification->hasError()) {
            throw new ValidationNotificationException($this->notification->messages());
        }
    }
}

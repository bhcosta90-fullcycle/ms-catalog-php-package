<?php

use BRCas\CA\Domain\Exceptions\EntityValidationException;
use BRCas\CA\Domain\ValueObject\Uuid;
use BRCas\MV\Domain\ValueObject\{Image, Media};
use BRCas\MV\Domain\Entity\Video;
use BRCas\MV\Domain\Enum\{MediaStatus, Rating};
use Ramsey\Uuid\Uuid as UuidUuid;

test('attributes', function () {
    $domain = new Video(
        id: new Uuid($id = (string) UuidUuid::uuid4()),
        title: 'title',
        description: 'description',
        yearLaunched: 2010,
        duration: 50,
        opened: true,
        rating: Rating::L,
        publish: true,
        createdAt: $dateTime = new DateTime(),
    );

    expect($domain->id())->toBe($id);
    expect($domain->title)->toBe('title');
    expect($domain->description)->toBe('description');
    expect($domain->yearLaunched)->toBe(2010);
    expect($domain->duration)->toBe(50);
    expect($domain->opened)->toBeTrue();
    expect($domain->rating->value)->toBe('L');
    expect($domain->publish)->toBeTrue();

    expect($domain->thumbFile()?->path())->toBeNull();
    expect($domain->thumbHalf()?->path())->toBeNull();
    expect($domain->bannerFile()?->path())->toBeNull();

    expect($domain->trailerFile()?->path)->toBeNull();
    expect($domain->trailerFile()?->status)->toBeNull();
    expect($domain->trailerFile()?->encoded)->toBeNull();

    expect($domain->videoFile()?->path)->toBeNull();
    expect($domain->videoFile()?->status)->toBeNull();
    expect($domain->videoFile()?->encoded)->toBeNull();

    expect($domain->createdAt())->toBe($dateTime->format('Y-m-d H:i:s'));
});

test('test id and created at not passed', function () {
    $domain = new Video(
        title: 'title',
        description: 'description',
        yearLaunched: 2010,
        duration: 50,
        opened: true,
        rating: Rating::L,
    );

    expect($domain->id())->not->toBeEmpty();
    expect($domain->createdAt())->not->toBeEmpty();
});

test('add categories at video', function () {
    $domain = new Video(
        title: 'title',
        description: 'description',
        yearLaunched: 2010,
        duration: 50,
        opened: true,
        rating: Rating::L,
    );

    $domain->addCategory('123');
    $domain->addCategory('456');

    expect($domain->categories)->toHaveCount(2);
});

test('remove categories at video', function () {
    $domain = new Video(
        title: 'title',
        description: 'description',
        yearLaunched: 2010,
        duration: 50,
        opened: true,
        rating: Rating::L,
        categories: ['456', '789']
    );

    $domain->removeCategory('123');
    expect($domain->categories)->toHaveCount(2);

    $domain->removeCategory('456');
    expect($domain->categories)->toHaveCount(1);
});

test('add genres at video', function () {
    $domain = new Video(
        title: 'title',
        description: 'description',
        yearLaunched: 2010,
        duration: 50,
        opened: true,
        rating: Rating::L,
    );

    $domain->addGenre('123');
    $domain->addGenre('456');

    expect($domain->genres)->toHaveCount(2);
});

test('remove genres at video', function () {
    $domain = new Video(
        title: 'title',
        description: 'description',
        yearLaunched: 2010,
        duration: 50,
        opened: true,
        rating: Rating::L,
        genres: ['456', '789']
    );

    $domain->removeGenre('123');
    expect($domain->genres)->toHaveCount(2);

    $domain->removeGenre('456');
    expect($domain->genres)->toHaveCount(1);
});

test('add cast member at video', function () {
    $domain = new Video(
        title: 'title',
        description: 'description',
        yearLaunched: 2010,
        duration: 50,
        opened: true,
        rating: Rating::L,
    );

    $domain->addCastMember('123');
    $domain->addCastMember('456');

    expect($domain->castMembers)->toHaveCount(2);
});

test('remove cast member at video', function () {
    $domain = new Video(
        title: 'title',
        description: 'description',
        yearLaunched: 2010,
        duration: 50,
        opened: true,
        rating: Rating::L,
        castMembers: ['456', '789']
    );

    $domain->removeCastMember('123');
    expect($domain->castMembers)->toHaveCount(2);

    $domain->removeCastMember('456');
    expect($domain->castMembers)->toHaveCount(1);
});

test("value object image", function () {
    $domain = new Video(
        title: 'title',
        description: 'description',
        yearLaunched: 2010,
        duration: 50,
        opened: true,
        rating: Rating::L,
        thumbFile: new Image('testing/testing-thumb.jpg'),
        thumbHalf: new Image('testing/testing-half.jpg'),
        bannerFile: new Image('testing/banner-file.jpg'),
    );

    expect($domain->thumbFile()->path())->toBe('testing/testing-thumb.jpg');
    expect($domain->thumbHalf()->path())->toBe('testing/testing-half.jpg');
    expect($domain->bannerFile()->path())->toBe('testing/banner-file.jpg');
});

test("value object media", function () {
    $domain = new Video(
        title: 'title',
        description: 'description',
        yearLaunched: 2010,
        duration: 50,
        opened: true,
        rating: Rating::L,
        trailerFile: new Media(
            path: 'testing/trailer-file.mp4',
            status: MediaStatus::PENDING,
            encoded: 'testing/trailer-file.extension'
        ),
        videoFile: new Media(
            path: 'testing/trailer-file.mp4',
            status: MediaStatus::PENDING,
        ),
    );

    expect($domain->trailerFile()->path)->toBe('testing/trailer-file.mp4');
    expect($domain->trailerFile()->status->value)->toBe(2);
    expect($domain->trailerFile()->encoded)->toBe('testing/trailer-file.extension');

    expect($domain->videoFile()->path)->toBe('testing/trailer-file.mp4');
    expect($domain->videoFile()->status->value)->toBe(2);
    expect($domain->videoFile()->encoded)->toBeNull();
});

test("validation", function () {
    new Video(
        title: 'ti',
        description: 'de',
        yearLaunched: 2010,
        duration: 50,
        opened: true,
        rating: Rating::L,
    );
})->throws(EntityValidationException::class);

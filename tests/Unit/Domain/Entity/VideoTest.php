<?php

use BRCas\CA\Domain\ValueObject\Uuid;
use BRCas\MV\Domain\Entity\Video;
use BRCas\MV\Domain\Enum\Rating;
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
    );

    expect($domain->id())->toBe($id);
    expect($domain->title)->toBe('title');
    expect($domain->description)->toBe('description');
    expect($domain->yearLaunched)->toBe(2010);
    expect($domain->duration)->toBe(50);
    expect($domain->opened)->toBeTrue();
    expect($domain->rating->value)->toBe('L');
    expect($domain->publish)->toBeFalse();
});

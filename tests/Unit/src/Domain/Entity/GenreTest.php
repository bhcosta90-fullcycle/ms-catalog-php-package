<?php

// use BRCas\CA\Domain\Exceptions\EntityValidationException;
// use BRCas\CA\Domain\ValueObject\Uuid;

use BRCas\CA\Domain\Exceptions\EntityValidationException;
use BRCas\CA\Domain\ValueObject\Uuid;
use BRCas\MV\Domain\Entity\Genre;

test('create', function () {
    $genre = new Genre(
        name: 'new genre',
        isActive: true,
    );

    expect($genre->id())->not->toBeEmpty();
    expect($genre->createdAt())->not->toBeEmpty();
    expect($genre->name)->toBe('new genre');
    expect($genre->isActive)->toBeTrue();
});

test('change to enable', function () {
    $genre = new Genre(
        name: 'new genre',
        isActive: false,
    );

    expect($genre->isActive)->toBeFalse();
    $genre->enable();
    expect($genre->isActive)->toBeTrue();
});

test('change to disabled', function () {
    $genre = new Genre(
        name: 'new genre',
        isActive: true,
    );

    expect($genre->isActive)->toBeTrue();
    $genre->disable();
    expect($genre->isActive)->toBeFalse();
});

test('update', function () {
    $genre = new Genre(
        id: $id = Uuid::make(),
        name: 'new genre',
        isActive: true,
        createdAt: $date = new DateTime('2020-01-01 00:00:00')
    );

    $genre->update(name: 'update name');

    expect($genre->id())->toBe((string) $id);
    expect($genre->createdAt())->toBe($date->format('Y-m-d H:i:s'));
    expect($genre->name)->toBe('update name');
});

test("exception min name", function () {
    new Genre(
        name: 'ne',
        isActive: true,
    );
})->throws(EntityValidationException::class);

test("exception max name", function () {
    new Genre(
        name: str_repeat('n', 256),
        isActive: true,
    );
})->throws(EntityValidationException::class);

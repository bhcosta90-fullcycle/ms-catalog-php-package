<?php

use BRCas\CA\Domain\Exceptions\EntityValidationException;
use BRCas\CA\Domain\ValueObject\Uuid;
use BRCas\MV\Domain\Entity\Genre;
use Ramsey\Uuid\Uuid as UuidUuid;

test('attributes', function () {
    $domain = new Genre(
        id: new Uuid($id = (string) UuidUuid::uuid4()),
        name: 'testing',
        createdAt: $dateTime = new DateTime(),
        categories: ['123', '456']
    );

    expect($domain->id())->toBe($id);
    expect($domain->name)->toBe('testing');
    expect($domain->categories)->toBe(['123', '456']);
    expect($domain->createdAt())->toBe($dateTime->format('Y-m-d H:i:s'));
});

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

test("add category in genre", function () {
    $categoryId = (string) UuidUuid::uuid4();

    $genre = new Genre(
        name: 'testing',
        isActive: true,
    );

    $this->assertIsArray($genre->categories);
    $this->assertCount(0, $genre->categories);

    $genre->addCategory(category: $categoryId);
    $genre->addCategory(category: $categoryId);
    $this->assertCount(2, $genre->categories);
});

test("remove category in genre", function () {
    $categoryId = 'a3885b3c-f728-4ab5-9d13-52c89c33e587';
    $categoryId2 = '9a879379-7324-484b-a6d4-611040b5d2fb';

    $genre = new Genre(
        name: 'testing',
        isActive: true,
        categories: [$categoryId, $categoryId2]
    );

    $this->assertCount(2, $genre->categories);
    $genre->removeCategory('a3885b3c-f728-4ab5-9d13-52c89c33e587');
    $this->assertEquals($genre->categories, [
        '9a879379-7324-484b-a6d4-611040b5d2fb'
    ]);
});

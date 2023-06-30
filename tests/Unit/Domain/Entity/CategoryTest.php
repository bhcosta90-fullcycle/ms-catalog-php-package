<?php

use BRCas\CA\Domain\Exceptions\EntityValidationException;
use BRCas\CA\Domain\ValueObject\Uuid;
use BRCas\MV\Domain\Entity\Category;
use Ramsey\Uuid\Uuid as UuidUuid;

test('attributes', function () {
    $domain = new Category(
        id: $id = (string) UuidUuid::uuid4(),
        name: 'testing',
        description: 'testing description',
        createdAt: $dateTime = (new DateTime())->format('Y-m-d H:i:s')
    );

    expect($domain->id())->toBe($id);
    expect($domain->name)->toBe('testing');
    expect($domain->description)->toBe('testing description');
    expect($domain->createdAt())->toBe($dateTime);
});

test('create', function(){
    $category = new Category(
        name: 'new category',
        description: 'new description',
        isActive: true,
    );

    expect($category->id())->not->toBeEmpty();
    expect($category->createdAt())->not->toBeEmpty();
    expect($category->name)->toBe('new category');
    expect($category->description)->toBe('new description');
    expect($category->isActive)->toBeTrue();
});

test('change to enable', function () {
    $category = new Category(
        name: 'new category',
        isActive: false,
    );

    expect($category->isActive)->toBeFalse();
    $category->enable();
    expect($category->isActive)->toBeTrue();
});

test('change to disabled', function () {
    $category = new Category(
        name: 'new category',
        isActive: true,
    );
    
    expect($category->isActive)->toBeTrue();
    $category->disable();
    expect($category->isActive)->toBeFalse();
});

test('update', function () {
    $category = new Category(
        id: $id = (string) Uuid::make(),
        name: 'new category',
        description: 'new description',
        isActive: true,
        createdAt: $date = '2020-01-01 00:00:00'
    );

    $category->update(name: 'update name', description: 'update description');

    expect($category->id())->toBe((string) $id);
    expect($category->createdAt())->toBe($date);
    expect($category->name)->toBe('update name');
    expect($category->description)->toBe('update description');
});

test("exception min name", function () {
    new Category(
        name: 'ne',
        description: 'ne',
        isActive: true,
    );
})->throws(EntityValidationException::class);

test("exception max name", function () {
    new Category(
        name: str_repeat('n', 256),
        description: 'testing',
        isActive: true,
    );
})->throws(EntityValidationException::class);

test("exception min description", function () {
    new Category(
        name: 'new category',
        description: 'ne',
        isActive: true,
    );
})->throws(EntityValidationException::class);

test("exception max description", function () {
    new Category(
        name: 'new category',
        description: str_repeat('n', 256),
        isActive: true,
    );
})->throws(EntityValidationException::class);
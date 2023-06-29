<?php

use BRCas\CA\Domain\Exceptions\EntityValidationException;
use BRCas\CA\Domain\ValueObject\Uuid;
use BRCas\MV\Domain\Entity\CastMember;
use BRCas\MV\Domain\Enum\CastMemberType;

test('create', function(){
    $castMember = new CastMember(name: 'testing', type: CastMemberType::ACTOR);

    expect($castMember->id())->not->toBeEmpty();
    expect($castMember->createdAt())->not->toBeEmpty();
    expect($castMember->name)->toBe('testing');
    expect($castMember->type)->toBe(CastMemberType::ACTOR);
});

test('change to enable', function () {
    $castMember = new CastMember(
        name: 'new category',
        isActive: false,
        type: CastMemberType::ACTOR,
    );

    expect($castMember->isActive)->toBeFalse();
    $castMember->enable();
    expect($castMember->isActive)->toBeTrue();
});

test('change to disabled', function () {
    $castMember = new CastMember(
        name: 'new category',
        isActive: true,
        type: CastMemberType::ACTOR,
    );
    
    expect($castMember->isActive)->toBeTrue();
    $castMember->disable();
    expect($castMember->isActive)->toBeFalse();
});

test('update', function () {
    $castMember = new CastMember(
        id: $id = Uuid::make(),
        name: 'new category',
        type: CastMemberType::ACTOR,
        isActive: true,
        createdAt: $date = new DateTime('2020-01-01 00:00:00')
    );

    $castMember->update(name: 'update name');

    expect($castMember->id())->toBe((string) $id);
    expect($castMember->createdAt())->toBe($date->format('Y-m-d H:i:s'));
    expect($castMember->name)->toBe('update name');
    expect($castMember->type)->toBe(CastMemberType::ACTOR);
});

test("exception min name", function () {
    new CastMember(
        name: 'ne',
        type: CastMemberType::ACTOR,
        isActive: true,
    );
})->throws(EntityValidationException::class);

test("exception max name", function () {
    new CastMember(
        name: str_repeat('n', 256),
        type: CastMemberType::ACTOR,
        isActive: true,
    );
})->throws(EntityValidationException::class);
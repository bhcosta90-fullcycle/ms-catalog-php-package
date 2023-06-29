<?php

use BRCas\CA\Domain\ValueObject\Uuid;
use BRCas\MV\Domain\Entity\CastMember;
use BRCas\MV\Domain\Enum\CastMemberType;
use BRCas\MV\Domain\Repository\CastMemberRepositoryInterface;
use BRCas\MV\UseCases\CastMember\UpdateCastMemberUseCase;
use BRCas\MV\UseCases\CastMember\DTO\UpdateCastMember\Input;
use BRCas\MV\UseCases\CastMember\DTO\CastMemberOutput as Output;

test("update a domain when I wanna enable", function () {
    $entity = Mockery::mock(CastMember::class, $data = [
        $name = 'update category',
        CastMemberType::ACTOR,
        $active = true,
        $id = Uuid::make()
    ]);
    $entity->shouldReceive('id')->andReturn($id);
    $entity->shouldReceive('enable');
    $entity->shouldReceive('createdAt');
    $entity->shouldReceive('update')->with($name);

    $repository = Mockery::mock(CastMemberRepositoryInterface::class);
    $repository->shouldReceive('getById')->andReturn($entity);
    $repository->shouldReceive('update')->andReturn($entity);

    $useCase = new UpdateCastMemberUseCase(
        repository: $repository
    );

    $response = $useCase->execute(new Input(
        id: $data[3],
        name: $data[0],
        isActive: $data[2]
    ));

    expect($response)->toBeInstanceOf(Output::class);
    expect($response->id)->toBe((string) $id);
    expect($response->name)->toBe($data[0]);
    expect($response->is_active)->toBe($active);
    expect($response->type)->toBe(2);
    $entity->shouldHaveReceived('update')->times(1);
    $entity->shouldHaveReceived('enable')->times(1);
    $repository->shouldHaveReceived('getById')->times(1);
    $repository->shouldHaveReceived('update')->times(1);
});

test("update a domain when I wanna disabled", function () {
    $entity = Mockery::mock(CastMember::class, $data = [
        $name = 'update category',
        CastMemberType::ACTOR,
        $active = false,
        $id = Uuid::make()
    ]);
    $entity->shouldReceive('id')->andReturn($id);
    $entity->shouldReceive('disable');
    $entity->shouldReceive('createdAt');
    $entity->shouldReceive('update')->with($name);

    $repository = Mockery::mock(CastMemberRepositoryInterface::class);
    $repository->shouldReceive('getById')->andReturn($entity);
    $repository->shouldReceive('update')->andReturn($entity);

    $useCase = new UpdateCastMemberUseCase(
        repository: $repository
    );

    $response = $useCase->execute(new Input(
        id: $data[3],
        name: $data[0],
        isActive: $data[2]
    ));

    expect($response)->toBeInstanceOf(Output::class);
    expect($response->id)->toBe((string) $id);
    expect($response->name)->toBe($data[0]);
    expect($response->is_active)->toBe($active);
    expect($response->type)->toBe(2);
    $entity->shouldHaveReceived('disable')->times(1);
    $repository->shouldHaveReceived('getById')->times(1);
    $repository->shouldHaveReceived('update')->times(1);
});

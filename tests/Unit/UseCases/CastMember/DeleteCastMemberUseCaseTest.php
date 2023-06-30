<?php

use BRCas\CA\Domain\ValueObject\Uuid;
use BRCas\MV\Domain\Entity\CastMember;
use BRCas\MV\Domain\Enum\CastMemberType;
use BRCas\MV\Domain\Repository\CastMemberRepositoryInterface;
use BRCas\MV\UseCases\CastMember\DeleteCastMemberUseCase;
use BRCas\MV\UseCases\CastMember\DTO\CastMemberInput as Input;
use BRCas\MV\UseCases\CastMember\DTO\DeleteCastMember\Output;

test("delete the domain when the result is positive", function () {
    $entity = Mockery::mock(CastMember::class, $data = [
        'update category',
        CastMemberType::ACTOR,
        true,
        $id = Uuid::make()
    ]);
    $entity->shouldReceive('id')->andReturn($id);

    $repository = Mockery::mock(CastMemberRepositoryInterface::class);
    $repository->shouldReceive('getById')->andReturn($entity);
    $repository->shouldReceive('delete')->andReturn(true);

    $useCase = new DeleteCastMemberUseCase(
        repository: $repository
    );

    $response = $useCase->execute(new Input(
        id: $data[3],
    ));

    expect($response)->toBeInstanceOf(Output::class);
    expect($response->success)->toBeTrue();
    $repository->shouldHaveReceived('getById')->times(1);
    $repository->shouldHaveReceived('delete')->times(1);
});

test("delete the domain when the result is negative", function () {
    $entity = Mockery::mock(CastMember::class, $data = [
        'update category',
        CastMemberType::ACTOR,
        true,
        $id = Uuid::make()
    ]);
    $entity->shouldReceive('id')->andReturn($id);

    $repository = Mockery::mock(CastMemberRepositoryInterface::class);
    $repository->shouldReceive('getById')->andReturn($entity);
    $repository->shouldReceive('delete')->andReturn(false);

    $useCase = new DeleteCastMemberUseCase(
        repository: $repository
    );

    $response = $useCase->execute(new Input(
        id: $data[3],
    ));

    expect($response)->toBeInstanceOf(Output::class);
    expect($response->success)->toBeFalse();
    $repository->shouldHaveReceived('getById')->times(1);
    $repository->shouldHaveReceived('delete')->times(1);
});

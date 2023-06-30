<?php

use BRCas\CA\Domain\ValueObject\Uuid;
use BRCas\MV\Domain\Entity\CastMember;
use BRCas\MV\Domain\Enum\CastMemberType;
use BRCas\MV\Domain\Repository\CastMemberRepositoryInterface;
use BRCas\MV\UseCases\CastMember\ListCastMemberUseCase;
use BRCas\MV\UseCases\CastMember\DTO\CastMemberInput as Input;
use BRCas\MV\UseCases\CastMember\DTO\CastMemberOutput as Output;

test("list a category", function () {
    $entity = Mockery::mock(CastMember::class, $data = [
        'new category',
        CastMemberType::ACTOR
    ]);
    $entity->shouldReceive('id')->andReturn($id = Uuid::make());
    $entity->shouldReceive('createdAt');

    $repository = Mockery::mock(CastMemberRepositoryInterface::class);
    $repository->shouldReceive('getById')->with((string) $id)->andReturn($entity);

    $useCase = new ListCastMemberUseCase(
        repository: $repository
    );
    
    $response = $useCase->execute(new Input(
        id: $id
    ));
    
    expect($response)->toBeInstanceOf(Output::class);
    expect($response->id)->toBe((string) $id);
    expect($response->name)->toBe($data[0]);
    expect($response->type)->toBe(2);
    $repository->shouldHaveReceived('getById')->times(1);
});

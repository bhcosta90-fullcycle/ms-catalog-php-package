<?php

use BRCas\CA\Domain\ValueObject\Uuid;
use BRCas\MV\Domain\Entity\CastMember;
use BRCas\MV\Domain\Enum\CastMemberType;
use BRCas\MV\Domain\Repository\CastMemberRepositoryInterface;
use BRCas\MV\UseCases\CastMember\CreateCastMemberUseCase;
use BRCas\MV\UseCases\CastMember\DTO\CreateCastMember\Input;
use BRCas\MV\UseCases\CastMember\DTO\CastMemberOutput as Output;

test("create a new domain", function () {
    $entity = Mockery::mock(CastMember::class, $data = [
        'new category',
        CastMemberType::ACTOR,
    ]);
    $entity->shouldReceive('id')->andReturn($id = Uuid::make());
    $entity->shouldReceive('createdAt');

    $repository = Mockery::mock(CastMemberRepositoryInterface::class);
    $repository->shouldReceive('insert')->andReturn($entity);

    $useCase = new CreateCastMemberUseCase(
        repository: $repository
    );
    
    $response = $useCase->execute(new Input(
        name: $data[0],
        type: $data[1]->value,
    ));
    
    expect($response)->toBeInstanceOf(Output::class);
    expect($response->id)->toBe((string) $id);
    expect($response->name)->toBe($data[0]);
    expect($response->type)->toBe(2);
    $repository->shouldHaveReceived('insert')->times(1);
});

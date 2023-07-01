<?php

use BRCas\CA\Domain\ValueObject\Uuid;
use BRCas\MV\Domain\Entity\Video;
use BRCas\MV\Domain\Enum\Rating;
use BRCas\MV\Domain\Enum\VideoType;
use BRCas\MV\Domain\Repository\VideoRepositoryInterface;
use BRCas\MV\UseCases\Video\DeleteVideoUseCase;
use BRCas\MV\UseCases\Video\DTO\ListVideoInput as Input;
use BRCas\MV\UseCases\Video\DTO\DeleteVideoOutput as Output;

test("delete the domain when the result is positive", function () {
    $entity = Mockery::mock(Video::class, $data = [
        'testing',
        'description',
        2010,
        50,
        true,
        Rating::L,
        null,
        null,
        null,
        null,
        null,
        [],
        [],
        [],
        false,
        $id = Uuid::make()
    ]);
    $entity->shouldReceive('id')->andReturn($id);

    $repository = Mockery::mock(VideoRepositoryInterface::class);
    $repository->shouldReceive('getById')->andReturn($entity);
    $repository->shouldReceive('delete')->andReturn(true);

    $useCase = new DeleteVideoUseCase(
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
    $entity = Mockery::mock(Video::class, $data = [
        'testing',
        'description',
        2010,
        50,
        true,
        Rating::L,
        null,
        null,
        null,
        null,
        null,
        [],
        [],
        [],
        false,
        $id = Uuid::make()
    ]);
    $entity->shouldReceive('id')->andReturn($id);

    $repository = Mockery::mock(VideoRepositoryInterface::class);
    $repository->shouldReceive('getById')->andReturn($entity);
    $repository->shouldReceive('delete')->andReturn(false);

    $useCase = new DeleteVideoUseCase(
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

<?php

use BRCas\CA\Domain\ValueObject\Uuid;
use BRCas\MV\Domain\Entity\Genre;
use BRCas\MV\Domain\Repository\GenreRepositoryInterface;
use BRCas\MV\UseCases\Genre\DeleteGenreUseCase;
use BRCas\MV\UseCases\Genre\DTO\GenreInput as Input;
use BRCas\MV\UseCases\Genre\DTO\DeleteGenre\Output;

test("delete the domain when the result is positive", function () {
    $entity = Mockery::mock(Genre::class, $data = [
        'update category',
        [],
        true,
        $id = Uuid::make()
    ]);
    $entity->shouldReceive('id')->andReturn($id);

    $repository = Mockery::mock(GenreRepositoryInterface::class);
    $repository->shouldReceive('getById')->andReturn($entity);
    $repository->shouldReceive('delete')->andReturn(true);

    $useCase = new DeleteGenreUseCase(
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
    $entity = Mockery::mock(Genre::class, $data = [
        'update category',
        [],
        true,
        $id = Uuid::make()
    ]);
    $entity->shouldReceive('id')->andReturn($id);
    $entity->shouldReceive('enable');

    $repository = Mockery::mock(GenreRepositoryInterface::class);
    $repository->shouldReceive('getById')->andReturn($entity);
    $repository->shouldReceive('delete')->andReturn(false);

    $useCase = new DeleteGenreUseCase(
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

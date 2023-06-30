<?php

use BRCas\CA\Domain\ValueObject\Uuid;
use BRCas\MV\Domain\Entity\Genre;
use BRCas\MV\Domain\Repository\GenreRepositoryInterface;
use BRCas\MV\UseCases\Genre\ListGenreUseCase;
use BRCas\MV\UseCases\Genre\DTO\GenreInput as Input;
use BRCas\MV\UseCases\Genre\DTO\GenreOutput as Output;

test("list a genre", function () {
    $entity = Mockery::mock(Genre::class, $data = [
        'new genre'
    ]);
    $entity->shouldReceive('id')->andReturn($id = Uuid::make());
    $entity->shouldReceive('createdAt');

    $repository = Mockery::mock(GenreRepositoryInterface::class);
    $repository->shouldReceive('getById')->with((string) $id)->andReturn($entity);

    $useCase = new ListGenreUseCase(
        repository: $repository
    );
    
    $response = $useCase->execute(new Input(
        id: $id
    ));
    
    expect($response)->toBeInstanceOf(Output::class);
    expect($response->id)->toBe((string) $id);
    expect($response->name)->toBe($data[0]);
    $repository->shouldHaveReceived('getById')->times(1);
});

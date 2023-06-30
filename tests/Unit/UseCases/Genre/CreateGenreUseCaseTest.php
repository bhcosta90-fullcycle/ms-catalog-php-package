<?php

use BRCas\CA\Domain\Exceptions\EntityNotFoundException;
use BRCas\CA\Domain\ValueObject\Uuid;
use BRCas\MV\Domain\Entity\Genre;
use BRCas\MV\Domain\Repository\CategoryRepositoryInterface;
use BRCas\MV\Domain\Repository\GenreRepositoryInterface;
use BRCas\MV\UseCases\Genre\CreateGenreUseCase;
use BRCas\MV\UseCases\Genre\DTO\CreateGenre\Input;
use BRCas\MV\UseCases\Genre\DTO\GenreOutput as Output;

beforeEach(function () {
    $mockCategoryRepository = Mockery::mock(CategoryRepositoryInterface::class);
    $mockCategoryRepository->shouldReceive('getIdsByListId')->andReturn(mockKeyValue($this->categories = [
        'abc' => 'abc',
        'def' => 'def'
    ]));

    $entity = Mockery::mock(Genre::class, $this->data = [
        'new category',
        array_keys($this->categories),
    ]);
    $entity->shouldReceive('id')->andReturn($this->id = Uuid::make());
    $entity->shouldReceive('createdAt');

    $repository = Mockery::mock(GenreRepositoryInterface::class);
    $repository->shouldReceive('insert')->andReturn($entity);

    $this->repository = $repository;

    $this->useCase = new CreateGenreUseCase(
        repository: $repository,
        transaction: mockTransaction(),
        category: $mockCategoryRepository,
    );
});

test("create a new domain", function () {
    $response = $this->useCase->execute(new Input(
        name: $this->data[0],
        categories: array_keys($this->categories)
    ));

    expect($response)->toBeInstanceOf(Output::class);
    expect($response->id)->toBe((string) $this->id);
    expect($response->name)->toBe($this->data[0]);
    expect($response->is_active)->toBeTrue();
    expect($response->categories)->toBe(['abc', 'def']);
    $this->repository->shouldHaveReceived('insert')->times(1);
});

test("create a new domain with exception category", function () {
    $this->useCase->execute(new Input(
        name: $this->data[0],
        categories: ['1']
    ));
})->throws(EntityNotFoundException::class);

test("create a new domain with exception two categories", function () {
    $this->useCase->execute(new Input(
        name: $this->data[0],
        categories: ['1', '2']
    ));
})->throws(EntityNotFoundException::class, 'Categories 1, 2 not found');

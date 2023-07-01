<?php

use BRCas\CA\Domain\Exceptions\EntityNotFoundException;
use BRCas\CA\Domain\ValueObject\Uuid;
use BRCas\MV\Domain\Entity\Genre;
use BRCas\MV\Domain\Repository\CategoryRepositoryInterface;
use BRCas\MV\Domain\Repository\GenreRepositoryInterface;
use BRCas\MV\UseCases\Genre\UpdateGenreUseCase;
use BRCas\MV\UseCases\Genre\DTO\UpdateGenre\Input;
use BRCas\MV\UseCases\Genre\DTO\GenreOutput as Output;

beforeEach(function () {
    $mockCategoryRepository = Mockery::mock(CategoryRepositoryInterface::class);
    $mockCategoryRepository->shouldReceive('getIdsByListId')->andReturn(mockKeyValue($this->categories = [
        'abc',
        'def'
    ]));

    $entity = Mockery::mock(Genre::class, $this->data = [
        'new category',
        array_keys($this->categories),
        true,
        $this->id = Uuid::make()
    ]);
    $entity->shouldReceive('id')->andReturn($this->id);
    $entity->shouldReceive('createdAt');
    $entity->shouldReceive('update');
    $entity->shouldReceive('enable');
    $entity->shouldReceive('disable');
    $entity->shouldReceive('addCategory');
    $this->entity = $entity;

    $repository = Mockery::mock(GenreRepositoryInterface::class);
    $repository->shouldReceive('getById')->andReturn($entity);
    $repository->shouldReceive('update')->andReturn($entity);

    $this->repository = $repository;

    $this->useCase = new UpdateGenreUseCase(
        repository: $repository,
        transaction: mockTransaction(),
        category: $mockCategoryRepository,
    );
});

test("update a domain -> enable", function () {
    $response = $this->useCase->execute(new Input(
        id: $this->id,
        name: $this->data[0],
        categories: array_keys($this->categories)
    ));

    expect($response)->toBeInstanceOf(Output::class);
    expect($response->id)->toBe((string) $this->id);
    expect($response->name)->toBe($this->data[0]);
    expect($response->is_active)->toBeTrue();
    expect($response->categories)->toBe(['abc', 'def']);
    $this->repository->shouldHaveReceived('getById')->times(1);
    $this->repository->shouldHaveReceived('update')->times(1);
    $this->entity->shouldHaveReceived('addCategory')->times(2);
    $this->entity->shouldHaveReceived('update')->times(1);
    $this->entity->shouldNotHaveReceived('disable');
    $this->entity->shouldHaveReceived('enable')->times(1);
});

test("update a domain -> disabled", function () {
    $response = $this->useCase->execute(new Input(
        id: $this->id,
        name: $this->data[0],
        categories: array_keys($this->categories),
        isActive: false,
    ));

    expect($response)->toBeInstanceOf(Output::class);
    expect($response->id)->toBe((string) $this->id);
    expect($response->name)->toBe($this->data[0]);
    expect($response->is_active)->toBeTrue();
    expect($response->categories)->toBe(['abc', 'def']);
    $this->repository->shouldHaveReceived('getById')->times(1);
    $this->repository->shouldHaveReceived('update')->times(1);
    $this->entity->shouldHaveReceived('addCategory')->times(2);
    $this->entity->shouldHaveReceived('update')->times(1);
    $this->entity->shouldNotHaveReceived('enable');
    $this->entity->shouldHaveReceived('disable')->times(1);
});

test("update a domain with exception category", function () {
    $this->useCase->execute(new Input(
        id: $this->id,
        name: $this->data[0],
        categories: ['1']
    ));
})->throws(EntityNotFoundException::class);

test("update a domain with exception two categories", function () {
    $this->useCase->execute(new Input(
        id: $this->id,
        name: $this->data[0],
        categories: ['1', '2']
    ));
})->throws(EntityNotFoundException::class, 'Categories 1, 2 not found');

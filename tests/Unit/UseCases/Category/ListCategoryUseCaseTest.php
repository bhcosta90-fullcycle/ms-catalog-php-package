<?php

use BRCas\CA\Domain\ValueObject\Uuid;
use BRCas\MV\Domain\Entity\Category;
use BRCas\MV\Domain\Repository\CategoryRepositoryInterface;
use BRCas\MV\UseCases\Category\ListCategoryUseCase;
use BRCas\MV\UseCases\Category\DTO\CategoryInput as Input;
use BRCas\MV\UseCases\Category\DTO\CategoryOutput as Output;

test("list a category", function () {
    $entity = Mockery::mock(Category::class, $data = [
        'new category'
    ]);
    $entity->shouldReceive('id')->andReturn($id = Uuid::make());
    $entity->shouldReceive('createdAt');

    $repository = Mockery::mock(CategoryRepositoryInterface::class);
    $repository->shouldReceive('getById')->with((string) $id)->andReturn($entity);

    $useCase = new ListCategoryUseCase(
        repository: $repository
    );
    
    $response = $useCase->execute(new Input(
        id: $id
    ));
    
    expect($response)->toBeInstanceOf(Output::class);
    expect($response->id)->toBe((string) $id);
    expect($response->name)->toBe($data[0]);
    expect($response->description)->toBeNull();
    $repository->shouldHaveReceived('getById')->times(1);
});

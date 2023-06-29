<?php

use BRCas\CA\Domain\ValueObject\Uuid;
use Core\Domain\Entity\Category;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\UseCases\Category\ListCategoryUseCase;
use Core\UseCases\Category\DTO\ListCategoryInput;
use Core\UseCases\Category\DTO\ListCategoryOutput;

test("list a category", function () {
    $entity = Mockery::mock(Category::class, $data = [
        'new category'
    ]);
    $entity->shouldReceive('id')->andReturn($id = Uuid::make());

    $repository = Mockery::mock(CategoryRepositoryInterface::class);
    $repository->shouldReceive('find')->with((string) $id)->andReturn($entity);

    $useCase = new ListCategoryUseCase(
        repository: $repository
    );
    
    $response = $useCase->execute(new ListCategoryInput(
        id: $id
    ));
    
    expect($response)->toBeInstanceOf(ListCategoryOutput::class);
    expect($response->id)->toBe((string) $id);
    expect($response->name)->toBe($data[0]);
    expect($response->description)->toBeNull();
    $repository->shouldHaveReceived('find')->times(1);
});

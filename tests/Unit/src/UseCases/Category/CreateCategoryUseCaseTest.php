<?php

use BRCas\CA\Domain\ValueObject\Uuid;
use Core\Domain\Entity\Category;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\UseCases\Category\CreateCategoryUseCase;
use Core\UseCases\Category\DTO\CreateCategoryInput;
use Core\UseCases\Category\DTO\CreateCategoryOutput;

test("create a new domain", function () {
    $entity = Mockery::mock(Category::class, $data = [
        'new category'
    ]);
    $entity->shouldReceive('id')->andReturn($id = Uuid::make());

    $repository = Mockery::mock(CategoryRepositoryInterface::class);
    $repository->shouldReceive('insert')->andReturn($entity);

    $useCase = new CreateCategoryUseCase(
        repository: $repository
    );
    
    $response = $useCase->execute(new CreateCategoryInput(
        name: $data[0]
    ));
    
    expect($response)->toBeInstanceOf(CreateCategoryOutput::class);
    expect($response->id)->toBe((string) $id);
    expect($response->name)->toBe($data[0]);
    expect($response->description)->toBeNull();
    $repository->shouldHaveReceived('insert')->times(1);
});

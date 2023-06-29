<?php

use BRCas\CA\Domain\ValueObject\Uuid;
use BRCas\MV\Domain\Entity\Category;
use BRCas\MV\Domain\Repository\CategoryRepositoryInterface;
use BRCas\MV\UseCases\Category\CreateCategoryUseCase;
use BRCas\MV\UseCases\Category\DTO\CreateCategory\Input;
use BRCas\MV\UseCases\Category\DTO\CreateCategory\Output;

test("create a new domain", function () {
    $entity = Mockery::mock(Category::class, $data = [
        'new category'
    ]);
    $entity->shouldReceive('id')->andReturn($id = Uuid::make());
    $entity->shouldReceive('createdAt');

    $repository = Mockery::mock(CategoryRepositoryInterface::class);
    $repository->shouldReceive('insert')->andReturn($entity);

    $useCase = new CreateCategoryUseCase(
        repository: $repository
    );
    
    $response = $useCase->execute(new Input(
        name: $data[0]
    ));
    
    expect($response)->toBeInstanceOf(Output::class);
    expect($response->id)->toBe((string) $id);
    expect($response->name)->toBe($data[0]);
    expect($response->description)->toBeNull();
    $repository->shouldHaveReceived('insert')->times(1);
});

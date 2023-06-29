<?php

use BRCas\CA\Domain\ValueObject\Uuid;
use Core\Domain\Entity\Category;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\UseCases\Category\DeleteCategoryUseCase;
use Core\UseCases\Category\DTO\DeleteCategoryInput;
use Core\UseCases\Category\DTO\DeleteCategoryOutput;

test("delete the domain when the result is positive", function () {
    $entity = Mockery::mock(Category::class, $data = [
        $name = 'update category',
        $description = null,
        true,
        $id = (string) Uuid::make()
    ]);
    $entity->shouldReceive('id')->andReturn($id);
    $entity->shouldReceive('enable');
    $entity->shouldReceive('update')->with($name, $description);

    $repository = Mockery::mock(CategoryRepositoryInterface::class);
    $repository->shouldReceive('find')->andReturn($entity);
    $repository->shouldReceive('delete')->andReturn(true);

    $useCase = new DeleteCategoryUseCase(
        repository: $repository
    );

    $response = $useCase->execute(new DeleteCategoryInput(
        id: $data[3],
    ));

    expect($response)->toBeInstanceOf(DeleteCategoryOutput::class);
    expect($response->success)->toBeTrue();
    $repository->shouldHaveReceived('find')->times(1);
    $repository->shouldHaveReceived('delete')->times(1);
});

test("delete the domain when the result is negative", function () {
    $entity = Mockery::mock(Category::class, $data = [
        $name = 'update category',
        $description = null,
        true,
        $id = (string) Uuid::make()
    ]);
    $entity->shouldReceive('id')->andReturn($id);
    $entity->shouldReceive('enable');
    $entity->shouldReceive('update')->with($name, $description);

    $repository = Mockery::mock(CategoryRepositoryInterface::class);
    $repository->shouldReceive('find')->andReturn($entity);
    $repository->shouldReceive('delete')->andReturn(false);

    $useCase = new DeleteCategoryUseCase(
        repository: $repository
    );

    $response = $useCase->execute(new DeleteCategoryInput(
        id: $data[3],
    ));

    expect($response)->toBeInstanceOf(DeleteCategoryOutput::class);
    expect($response->success)->toBeFalse();
    $repository->shouldHaveReceived('find')->times(1);
    $repository->shouldHaveReceived('delete')->times(1);
});

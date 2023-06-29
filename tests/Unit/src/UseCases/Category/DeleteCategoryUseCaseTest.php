<?php

use BRCas\CA\Domain\ValueObject\Uuid;
use BRCas\MV\Domain\Entity\Category;
use BRCas\MV\Domain\Repository\CategoryRepositoryInterface;
use BRCas\MV\UseCases\Category\DeleteCategoryUseCase;
use BRCas\MV\UseCases\Category\DTO\DeleteCategory\Input;
use BRCas\MV\UseCases\Category\DTO\DeleteCategory\Output;

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
    $repository->shouldReceive('getById')->andReturn($entity);
    $repository->shouldReceive('delete')->andReturn(true);

    $useCase = new DeleteCategoryUseCase(
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
    $repository->shouldReceive('getById')->andReturn($entity);
    $repository->shouldReceive('delete')->andReturn(false);

    $useCase = new DeleteCategoryUseCase(
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

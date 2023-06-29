<?php

use BRCas\CA\Domain\ValueObject\Uuid;
use Core\Domain\Entity\Category;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\UseCases\Category\UpdateCategoryUseCase;
use Core\UseCases\Category\DTO\UpdateCategory\Input;
use Core\UseCases\Category\DTO\UpdateCategory\Output;

test("update a domain when I wanna enable", function () {
    $entity = Mockery::mock(Category::class, $data = [
        $name = 'update category',
        $description = null,
        $active = true,
        $id = (string) Uuid::make()
    ]);
    $entity->shouldReceive('id')->andReturn($id);
    $entity->shouldReceive('enable');
    $entity->shouldReceive('update')->with($name, $description);

    $repository = Mockery::mock(CategoryRepositoryInterface::class);
    $repository->shouldReceive('find')->andReturn($entity);
    $repository->shouldReceive('update')->andReturn($entity);

    $useCase = new UpdateCategoryUseCase(
        repository: $repository
    );

    $response = $useCase->execute(new Input(
        id: $data[3],
        name: $data[0],
        isActive: $data[2]
    ));

    expect($response)->toBeInstanceOf(Output::class);
    expect($response->id)->toBe((string) $id);
    expect($response->name)->toBe($data[0]);
    expect($response->is_active)->toBe($active);
    expect($response->description)->toBeNull();
    $entity->shouldHaveReceived('update')->times(1);
    $entity->shouldHaveReceived('enable')->times(1);
    $repository->shouldHaveReceived('find')->times(1);
    $repository->shouldHaveReceived('update')->times(1);
});


test("update a domain when I wanna disabled", function () {
    $entity = Mockery::mock(Category::class, $data = [
        $name = 'update category',
        $description = null,
        $active = false,
        $id = (string) Uuid::make()
    ]);
    $entity->shouldReceive('id')->andReturn($id);
    $entity->shouldReceive('disable');
    $entity->shouldReceive('update')->with($name, $description);

    $repository = Mockery::mock(CategoryRepositoryInterface::class);
    $repository->shouldReceive('find')->andReturn($entity);
    $repository->shouldReceive('update')->andReturn($entity);

    $useCase = new UpdateCategoryUseCase(
        repository: $repository
    );

    $response = $useCase->execute(new Input(
        id: $data[3],
        name: $data[0],
        isActive: $data[2]
    ));

    expect($response)->toBeInstanceOf(Output::class);
    expect($response->id)->toBe((string) $id);
    expect($response->name)->toBe($data[0]);
    expect($response->is_active)->toBe($active);
    expect($response->description)->toBe($description);
    $entity->shouldHaveReceived('disable')->times(1);
    $repository->shouldHaveReceived('find')->times(1);
    $repository->shouldHaveReceived('update')->times(1);
});

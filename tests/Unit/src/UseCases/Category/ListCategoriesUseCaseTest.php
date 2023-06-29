<?php

use BRCas\CA\Domain\ValueObject\Uuid;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\UseCases\Category\ListCategoriesUseCase;
use Core\UseCases\Category\DTO\ListCategories\Input;
use Core\UseCases\Category\DTO\ListCategories\Output;

test("list all when is empty", function () {
    $mockItem = mockPaginate();

    $repository = Mockery::mock(CategoryRepositoryInterface::class);
    $repository->shouldReceive('paginate')->andReturn($mockItem);

    $useCase = new ListCategoriesUseCase(
        repository: $repository
    );

    $response = $useCase->execute(new Input());

    expect($response)->toBeInstanceOf(Output::class);
    expect($response->items)->toHaveCount(0);
    expect($response->total)->toBe(0);
    expect($response->first_page)->toBe(0);
    expect($response->last_page)->toBe(0);
    expect($response->per_page)->toBe(0);
    expect($response->current_page)->toBe(0);
    expect($response->to)->toBe(0);
    expect($response->from)->toBe(0);
    $repository->shouldHaveReceived('paginate')->times(1);
});

test("list all when is not empty", function () {
    $class = new stdClass();
    $class->id = Uuid::make();
    $class->name = 'testing name';
    $class->description = 'testing description';
    $class->is_active = true;

    $mockItem = mockPaginate([$class]);

    $repository = Mockery::mock(CategoryRepositoryInterface::class);
    $repository->shouldReceive('paginate')->andReturn($mockItem);

    $useCase = new ListCategoriesUseCase(
        repository: $repository
    );

    $response = $useCase->execute(new Input());

    expect($response)->toBeInstanceOf(Output::class);
    expect($response->items)->toHaveCount(1);
    expect($response->total)->toBe(0);
    expect($response->first_page)->toBe(0);
    expect($response->last_page)->toBe(0);
    expect($response->per_page)->toBe(0);
    expect($response->current_page)->toBe(0);
    expect($response->to)->toBe(0);
    expect($response->from)->toBe(0);
    expect($response->items[0])->toBeInstanceOf(stdClass::class);
    $repository->shouldHaveReceived('paginate')->times(1);
});

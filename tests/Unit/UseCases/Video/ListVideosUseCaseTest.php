<?php

use BRCas\CA\Domain\ValueObject\Uuid;
use BRCas\CA\Repository\PaginateInterface;
use BRCas\MV\Domain\Repository\VideoRepositoryInterface;
use BRCas\MV\UseCases\Video\ListVideosUseCase;
use BRCas\MV\UseCases\Video\DTO\ListVideosOutput;

test("list all when is empty", function () {
    $mockItem = mockPaginate();

    $repository = Mockery::mock(VideoRepositoryInterface::class);
    $repository->shouldReceive('paginate')->andReturn($mockItem);

    $useCase = new ListVideosUseCase(
        repository: $repository
    );

    $response = $useCase->execute();

    expect($response)->toBeInstanceOf(PaginateInterface::class);
    expect($response->items())->toHaveCount(0);
    expect($response->total())->toBe(0);
    expect($response->firstPage())->toBe(0);
    expect($response->lastPage())->toBe(0);
    expect($response->perPage())->toBe(0);
    expect($response->currentPage())->toBe(0);
    expect($response->to())->toBe(0);
    expect($response->from())->toBe(0);
    $repository->shouldHaveReceived('paginate')->times(1);
});

test("list all when is not empty", function () {
    $class = new stdClass();
    $class->id = Uuid::make();
    $class->name = 'testing name';
    $class->description = 'testing description';
    $class->is_active = true;

    $mockItem = mockPaginate([$class]);

    $repository = Mockery::mock(VideoRepositoryInterface::class);
    $repository->shouldReceive('paginate')->andReturn($mockItem);

    $useCase = new ListVideosUseCase(
        repository: $repository
    );

    $response = $useCase->execute();

    expect($response)->toBeInstanceOf(PaginateInterface::class);
    expect($response->items())->toHaveCount(1);
    expect($response->total())->toBe(0);
    expect($response->firstPage())->toBe(0);
    expect($response->lastPage())->toBe(0);
    expect($response->perPage())->toBe(0);
    expect($response->currentPage())->toBe(0);
    expect($response->to())->toBe(0);
    expect($response->from())->toBe(0);
    expect($response->items()[0])->toBeInstanceOf(stdClass::class);
    $repository->shouldHaveReceived('paginate')->times(1);
});

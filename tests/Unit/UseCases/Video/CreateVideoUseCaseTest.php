<?php

use BRCas\CA\Domain\Exceptions\EntityNotFoundException;
use BRCas\CA\Domain\ValueObject\Uuid;
use BRCas\CA\UseCase\FileStorageInterface;
use BRCas\MV\Domain\Entity\Video;
use BRCas\MV\Domain\Enum\Rating;
use BRCas\MV\Domain\Repository\CastMemberRepositoryInterface;
use BRCas\MV\Domain\Repository\CategoryRepositoryInterface;
use BRCas\MV\Domain\Repository\GenreRepositoryInterface;
use BRCas\MV\Domain\Repository\VideoRepositoryInterface;
use BRCas\MV\UseCases\Video\CreateVideoUseCase;
use BRCas\MV\UseCases\Video\DTO\CreateVideoInput;
use BRCas\MV\UseCases\Video\DTO\VideoOutput;
use BRCas\MV\UseCases\Video\Interfaces\VideoEventManagerInterface;

$mockEntity = function () {
    $mock = Mockery::mock(Video::class, [
        'testing',
        'description',
        2010,
        50,
        true,
        Rating::L,
        null,
        null,
        null,
        null,
        null,
        [],
        [],
        [],
        false,
        $id = Uuid::make(),
        $date = new DateTime(),
    ]);

    $mock->shouldReceive('id')->andReturn((string) $id);
    $mock->shouldReceive('createdAt')->andReturn($date->format('Y-m-d H:i:s'));

    return $mock;
};

$mockRepository = function () use ($mockEntity) {
    $mock = Mockery::mock(VideoRepositoryInterface::class);
    $mock->shouldReceive('insert')->andReturn($mockEntity());
    return $mock;
};

$mockFileStorageInterface = function () {
    $mock = Mockery::mock(FileStorageInterface::class);
    $mock->shouldReceive('store');

    return $mock;
};

$mockEventManagerInterface = function () {
    return Mockery::mock(VideoEventManagerInterface::class);
};

$mockCategoryRepository = function ($items = []) {
    $newItems = [];
    foreach ($items as $item) {
        $newItems[$item] = $item;
    }

    $mock = Mockery::mock(CategoryRepositoryInterface::class);
    $mock->shouldReceive('getIdsByListId')->andReturn(mockKeyValue($newItems));
    return $mock;
};

$mockCastMemberRepository = function ($items = []) {
    $newItems = [];
    foreach ($items as $item) {
        $newItems[$item] = $item;
    }

    $mock = Mockery::mock(CastMemberRepositoryInterface::class);
    $mock->shouldReceive('getIdsByListId')->andReturn(mockKeyValue($newItems));
    return $mock;
};

$mockGenreRepository = function ($items = []) {
    $newItems = [];
    foreach ($items as $item) {
        $newItems[$item] = $item;
    }
    
    $mock = Mockery::mock(GenreRepositoryInterface::class);
    $mock->shouldReceive('getIdsByListId')->andReturn(mockKeyValue($newItems));
    return $mock;
};

$useCase = function (array $categories = [], array $castMembers = [], $genres = []) use (
    $mockRepository,
    $mockFileStorageInterface,
    $mockEventManagerInterface,
    $mockCategoryRepository,
    $mockCastMemberRepository,
    $mockGenreRepository
) {
    return new CreateVideoUseCase(
        repository: $mockRepository(),
        repositoryCategory: $mockCategoryRepository($categories),
        repositoryGenre: $mockGenreRepository($genres),
        repositoryCastMember: $mockCastMemberRepository($castMembers),
        transaction: mockTransaction(),
        storage: $mockFileStorageInterface(),
        eventManager: $mockEventManagerInterface(),
    );
};

test("execute", function () use ($useCase) {
    $response = $useCase()->execute(
        input: new CreateVideoInput(
            title: 'testing',
            description: 'description',
            yearLaunched: 2010,
            duration: 50,
            opened: true,
            rating: 'L',
        )
    );

    expect($response)->toBeInstanceOf(VideoOutput::class);
    expect($response->title)->toBe('testing');
    expect($response->description)->toBe('description');
    expect($response->yearLaunched)->toBe(2010);
    expect($response->duration)->toBe(50);
    expect($response->opened)->toBe(true);
    expect($response->rating)->toBe('L');
    expect($response->thumb_file)->toBeEmpty();
    expect($response->thumb_half)->toBeEmpty();
    expect($response->banner_file)->toBeEmpty();
    expect($response->trailer_file)->toBeEmpty();
    expect($response->video_file)->toBeEmpty();
    expect($response->id)->not->toBeEmpty();
    expect($response->created_at)->not->toBeEmpty();
});

test("execute send a categories at input -> exception", function () use ($useCase) {
    $useCase()->execute(
        input: new CreateVideoInput(
            title: 'testing',
            description: 'description',
            yearLaunched: 2010,
            duration: 50,
            opened: true,
            rating: 'L',
            categories: ['category-123456', 'category-654321']
        )
    );
})->throws(EntityNotFoundException::class, 'Categories category-123456, category-654321 not found');

test("execute send a categories at input -> validate", function () use ($useCase) {
    $response = $useCase(['category-123456', 'category-654321'])->execute(
        input: new CreateVideoInput(
            title: 'testing',
            description: 'description',
            yearLaunched: 2010,
            duration: 50,
            opened: true,
            rating: 'L',
            categories: $expect = ['category-123456', 'category-654321']
        )
    );
    expect($response->categories)->toBe($expect);
});

test("execute send a cast members at input -> exception", function () use ($useCase) {
    $useCase([])->execute(
        input: new CreateVideoInput(
            title: 'testing',
            description: 'description',
            yearLaunched: 2010,
            duration: 50,
            opened: true,
            rating: 'L',
            videoFile: ['path' => 'testing'],
            castMembers: ['123456', '654321']
        )
    );
})->throws(EntityNotFoundException::class, 'Cast members 123456, 654321 not found');

test("execute send a cast members at input -> validate", function () use ($useCase) {
    $response = $useCase([], ['cast-member-123456', 'cast-member-654321'])->execute(
        input: new CreateVideoInput(
            title: 'testing',
            description: 'description',
            yearLaunched: 2010,
            duration: 50,
            opened: true,
            rating: 'L',
            videoFile: ['path' => 'testing'],
            castMembers: $expect = ['cast-member-123456', 'cast-member-654321']
        )
    );
    expect($response->cast_members)->toBe($expect);
});

test("execute send a genres at input -> exception", function () use ($useCase) {
    $useCase([])->execute(
        input: new CreateVideoInput(
            title: 'testing',
            description: 'description',
            yearLaunched: 2010,
            duration: 50,
            opened: true,
            rating: 'L',
            videoFile: ['path' => 'testing'],
            genres: ['123456', '654321']
        )
    );
})->throws(EntityNotFoundException::class, 'Genres 123456, 654321 not found');

test("execute send a genres at input -> validate", function () use ($useCase) {
    $response = $useCase([], [], ['genre-123456', 'genre-654321'])->execute(
        input: new CreateVideoInput(
            title: 'testing',
            description: 'description',
            yearLaunched: 2010,
            duration: 50,
            opened: true,
            rating: 'L',
            videoFile: ['path' => 'testing'],
            genres: $expect = ['genre-123456', 'genre-654321']
        )
    );
    expect($response->genres)->toBe($expect);
});

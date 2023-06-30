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


test("execute", function () use (
    $mockRepository,
    $mockCategoryRepository,
    $mockGenreRepository,
    $mockCastMemberRepository,
    $mockFileStorageInterface,
    $mockEventManagerInterface
) {
    $useCase = new CreateVideoUseCase(
        repository: $mockRepository = $mockRepository(),
        repositoryCategory: $mockCategoryRepository = $mockCategoryRepository(),
        repositoryGenre: $mockGenreRepository = $mockGenreRepository(),
        repositoryCastMember: $mockCastMemberRepository = $mockCastMemberRepository(),
        transaction: mockTransaction(),
        storage: $mockFileStorageInterface = $mockFileStorageInterface(),
        eventManager: $mockEventManagerInterface = $mockEventManagerInterface(),
    );

    $response = $useCase->execute(
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

    $mockRepository->shouldHaveReceived('insert')->times(1);
});

test("execute send a categories at input -> exception", function ($data) use (
    $mockRepository,
    $mockCategoryRepository,
    $mockGenreRepository,
    $mockCastMemberRepository,
    $mockFileStorageInterface,
    $mockEventManagerInterface
) {
    $useCase = new CreateVideoUseCase(
        repository: $mockRepository = $mockRepository(),
        repositoryCategory: $mockCategoryRepository = $mockCategoryRepository(),
        repositoryGenre: $mockGenreRepository = $mockGenreRepository(),
        repositoryCastMember: $mockCastMemberRepository = $mockCastMemberRepository(),
        transaction: mockTransaction(),
        storage: $mockFileStorageInterface = $mockFileStorageInterface(),
        eventManager: $mockEventManagerInterface = $mockEventManagerInterface(),
    );

    try {
        $useCase->execute(
            input: new CreateVideoInput(
                title: 'testing',
                description: 'description',
                yearLaunched: 2010,
                duration: 50,
                opened: true,
                rating: 'L',
                categories: $data['categories'] ?? [],
                genres: $data['genres'] ?? [],
                castMembers: $data['cast-members'] ?? [],
            )
        );
    } catch (EntityNotFoundException $e) {
        expect($e->getMessage())->toBe($data['message']);
    }
})
    ->with([
        "category" => fn () => [
            'categories' => [
                'category-123456',
            ], 'message' => 'Category category-123456 not found'
        ],
        "categories" => fn () => [
            'categories' => [
                'category-123456',
                'category-654321'
            ], 'message' => 'Categories category-123456, category-654321 not found'
        ],
        "genre" => fn () => [
            'genres' => [
                'genre-123456',
            ], 'message' => 'Genre genre-123456 not found'
        ],
        "genres" => fn () => [
            'genres' => [
                'genre-123456',
                'genre-654321'
            ], 'message' => 'Genres genre-123456, genre-654321 not found'
        ],
        "cast member" => fn () => [
            'cast-members' => [
                'cast-members-123456',
            ], 'message' => 'Cast member cast-members-123456 not found'
        ],
        "cast members" => fn () => [
            'cast-members' => [
                'cast-members-123456',
                'cast-members-654321'
            ], 'message' => 'Cast members cast-members-123456, cast-members-654321 not found'
        ],
    ]);

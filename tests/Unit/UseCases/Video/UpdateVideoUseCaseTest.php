<?php

use BRCas\CA\Domain\Exceptions\{EntityNotFoundException, ValidationNotificationException};
use BRCas\CA\Domain\ValueObject\Uuid;
use BRCas\CA\UseCase\FileStorageInterface;
use BRCas\MV\Domain\Entity\Video;
use BRCas\MV\Domain\Enum\Rating;
use BRCas\MV\Domain\Repository\{
    CastMemberRepositoryInterface,
    CategoryRepositoryInterface,
    GenreRepositoryInterface,
    VideoRepositoryInterface
};
use BRCas\MV\UseCases\Video\UpdateVideoUseCase;
use BRCas\MV\UseCases\Video\DTO\UpdateVideoInput;
use BRCas\MV\UseCases\Video\DTO\VideoOutput;
use BRCas\MV\UseCases\Video\Interfaces\VideoEventManagerInterface;
use Ramsey\Uuid\Uuid as UuidUuid;

beforeEach(function () {
    $this->id = (string) UuidUuid::uuid4();
    $this->date = '2020-01-01 00:00:00';

    $this->entity = new Video(
        title: 'testing',
        description: 'testing description',
        yearLaunched: 2010,
        duration: 50,
        opened: true,
        rating: Rating::L,
        createdAt: new DateTime($this->date),
        id: new Uuid($this->id),
    );

    $this->mockRepository = Mockery::mock(VideoRepositoryInterface::class);
    $this->mockRepository->shouldReceive('getById')->andReturn($this->entity);
    $this->mockRepository->shouldReceive('update')->andReturn($this->entity);

    $this->mockFileStorageInterface = Mockery::mock(FileStorageInterface::class);
    $this->mockFileStorageInterface->shouldReceive('store')->andReturn(date('YmdHis'));

    $this->mockVideoEventManagerInterface = Mockery::mock(VideoEventManagerInterface::class);
    $this->mockVideoEventManagerInterface->shouldReceive('dispatch');

    $this->mockCategoryRepositoryInterface = Mockery::mock(CategoryRepositoryInterface::class);
    $this->mockCastMemberRepositoryInterface = Mockery::mock(CastMemberRepositoryInterface::class);
    $this->mockGenreRepositoryInterface = Mockery::mock(GenreRepositoryInterface::class);
});

test("execute simple", function () {
    $useCase = new UpdateVideoUseCase(
        repository: $this->mockRepository,
        repositoryCategory: $this->mockCategoryRepositoryInterface,
        repositoryGenre: $this->mockGenreRepositoryInterface,
        repositoryCastMember: $this->mockCastMemberRepositoryInterface,
        transaction: mockTransaction(),
        storage: $this->mockFileStorageInterface,
        eventManager: $this->mockVideoEventManagerInterface,
    );

    $response = $useCase->execute(
        input: new UpdateVideoInput(
            id: $this->id,
            title: 'testing',
            description: 'description',
            createdAt: $this->date,
        )
    );

    expect($response)->toBeInstanceOf(VideoOutput::class);
    expect($response->title)->toBe('testing');
    expect($response->description)->toBe('description');
    expect($response->year_launched)->toBe(2010);
    expect($response->duration)->toBe(50);
    expect($response->opened)->toBe(true);
    expect($response->rating)->toBe('L');
    expect($response->thumb_file)->toBeEmpty();
    expect($response->thumb_half)->toBeEmpty();
    expect($response->banner_file)->toBeEmpty();
    expect($response->trailer_file)->toBeEmpty();
    expect($response->video_file)->toBeEmpty();
    expect($response->id)->toBe($this->id);
    expect($response->created_at)->toBe($this->date);

    $this->mockRepository->shouldHaveReceived('getById')->times(1);
    $this->mockRepository->shouldHaveReceived('update')->times(1);
    $this->mockCategoryRepositoryInterface->shouldNotHaveReceived('getIdsByListId');
    $this->mockGenreRepositoryInterface->shouldNotHaveReceived('getIdsByListId');
    $this->mockCastMemberRepositoryInterface->shouldNotHaveReceived('getIdsByListId');
});

test("execute -> exception", function ($data) {
    $this->mockCategoryRepositoryInterface->shouldReceive('getIdsByListId')->andReturn(mockKeyValue());
    $this->mockCastMemberRepositoryInterface->shouldReceive('getIdsByListId')->andReturn(mockKeyValue());
    $this->mockGenreRepositoryInterface->shouldReceive('getIdsByListId')->andReturn(mockKeyValue());

    $useCase = new UpdateVideoUseCase(
        repository: $this->mockRepository,
        repositoryCategory: $this->mockCategoryRepositoryInterface,
        repositoryGenre: $this->mockGenreRepositoryInterface,
        repositoryCastMember: $this->mockCastMemberRepositoryInterface,
        transaction: mockTransaction(),
        storage: $this->mockFileStorageInterface,
        eventManager: $this->mockVideoEventManagerInterface,
    );

    try {

        $useCase->execute(
            input: new UpdateVideoInput(
                id: $this->id,
                title: array_key_exists('name', $data) ? $data['name'] : 'testing',
                description: $data['description'] ?? 'description',
                categories: $data['categories'] ?? [],
                genres: $data['genres'] ?? [],
                castMembers: $data['cast-members'] ?? [],
                createdAt: $this->date,
            )
        );
        expect(false)->toBeTrue();
    } catch (EntityNotFoundException | ValidationNotificationException $e) {
        expect($e->getMessage())->toBe($data['message']);
    }
})->with([
    "name is min length" => fn () => [
        'name' => 'a',
        'message' => 'video: The Title minimum is 3'
    ],
    "name is max length" => fn () => [
        'name' => str_repeat('a', 256),
        'message' => 'video: The Title maximum is 255'
    ],
    "description is min length" => fn () => [
        'description' => 'a',
        'message' => 'video: The Description minimum is 3'
    ],
    "description is max length" => fn () => [
        'description' => str_repeat('a', 256),
        'message' => 'video: The Description maximum is 255'
    ],
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

test("execute -> success with relationship", function ($data) {
    $this->mockCategoryRepositoryInterface->shouldReceive('getIdsByListId')->andReturn(mockKeyValue($data['categories'] ?? []));
    $this->mockGenreRepositoryInterface->shouldReceive('getIdsByListId')->andReturn(mockKeyValue($data['genres'] ?? []));
    $this->mockCastMemberRepositoryInterface->shouldReceive('getIdsByListId')->andReturn(mockKeyValue($data['cast-members'] ?? []));

    $useCase = new UpdateVideoUseCase(
        repository: $this->mockRepository,
        repositoryCategory: $this->mockCategoryRepositoryInterface,
        repositoryGenre: $this->mockGenreRepositoryInterface,
        repositoryCastMember: $this->mockCastMemberRepositoryInterface,
        transaction: mockTransaction(),
        storage: $this->mockFileStorageInterface,
        eventManager: $this->mockVideoEventManagerInterface,
    );

    $response = $useCase->execute(
        input: new UpdateVideoInput(
            id: $this->id,
            title: array_key_exists('name', $data) ? $data['name'] : 'testing',
            description: $data['description'] ?? 'description',
            categories: $data['categories'] ?? [],
            genres: $data['genres'] ?? [],
            castMembers: $data['cast-members'] ?? [],
            createdAt: $this->date,
        )
    );

    expect($response->categories)->toBe(array_values($data['categories'] ?? []));
    expect($response->genres)->toBe(array_values($data['genres'] ?? []));
    expect($response->cast_members)->toBe(array_values($data['cast-members'] ?? []));

    $this->mockRepository->shouldHaveReceived('update')->times(1);
    
    if ($data['categories'] ?? []) {
        $this->mockCategoryRepositoryInterface->shouldHaveReceived('getIdsByListId')->times(1);
    } else {
        $this->mockCategoryRepositoryInterface->shouldNotHaveReceived('getIdsByListId'); 
    }

    if ($data['genres'] ?? []) {
        $this->mockGenreRepositoryInterface->shouldHaveReceived('getIdsByListId')->times(1);
    } else {
        $this->mockGenreRepositoryInterface->shouldNotHaveReceived('getIdsByListId');
    }

    if ($data['cast-members'] ?? []) {
        $this->mockCastMemberRepositoryInterface->shouldHaveReceived('getIdsByListId')->times(1);
    } else {
        $this->mockCastMemberRepositoryInterface->shouldNotHaveReceived('getIdsByListId');
    }

})->with([
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

test('execute with files', function ($data) {
    $useCase = new UpdateVideoUseCase(
        repository: $this->mockRepository,
        repositoryCategory: $this->mockCategoryRepositoryInterface,
        repositoryGenre: $this->mockGenreRepositoryInterface,
        repositoryCastMember: $this->mockCastMemberRepositoryInterface,
        transaction: mockTransaction(),
        storage: $this->mockFileStorageInterface,
        eventManager: $this->mockVideoEventManagerInterface,
    );

    $response = $useCase->execute(
        input: new UpdateVideoInput(
            id: $this->id,
            title: 'testing',
            description: 'description',
            videoFile: $data['video-file'] ?? null,
            trailerFile: $data['trailer-file'] ?? null,
            bannerFile: $data['banner-file'] ?? null,
            thumbFile: $data['thumb-file'] ?? null,
            thumbHalf: $data['thumb-half'] ?? null,
            createdAt: $this->date,
        )
    );

    $total = 0;
    $dispatch = 0;

    if (!empty($data['video-file'])) {
        expect($response->video_file)->not->toBeEmpty();
        $total++;
        $dispatch = 1;
    }

    if (!empty($data['trailer-file'])) {
        expect($response->trailer_file)->not->toBeEmpty();
        $total++;
        $dispatch = 1;
    }

    if (!empty($data['banner-file'])) {
        expect($response->banner_file)->not->toBeEmpty();
        $total++;
    }

    if (!empty($data['thumb-file'])) {
        expect($response->thumb_file)->not->toBeEmpty();
        $total++;
    }

    if (!empty($data['thumb-half'])) {
        expect($response->thumb_half)->not->toBeEmpty();
        $total++;
    }

    if ($total > 0) {
        $this->mockFileStorageInterface->shouldHaveReceived('store')->times($total);
    } else {
        $this->mockFileStorageInterface->shouldNotHaveReceived('store');
    }

    if ($dispatch > 0) {
        $this->mockVideoEventManagerInterface->shouldHaveReceived('dispatch')->times(1);
    } else {
        $this->mockVideoEventManagerInterface->shouldNotHaveReceived('dispatch');
    }

})->with([
    "all-files" => fn () => [
        'video-file' => ['tmp' => '/tmp/testing-video-file'],
        'trailer-file' => ['tmp' => '/tmp/testing-trailer-file'],
        'banner-file' => ['tmp' => '/tmp/testing-banner-file'],
        'thumb-file' => ['tmp' => '/tmp/testing-thumb-file'],
        'thumb-half' => ['tmp' => '/tmp/testing-thumb-half'],
    ],
    "video-file" => fn () => [
        'video-file' => ['tmp' => '/tmp/testing-video-file'],
    ],
    "trailer-file" => fn () => [
        'trailer-file' => ['tmp' => '/tmp/testing-trailer-file'],
    ],
    "banner-file" => fn () => [
        'banner-file' => ['tmp' => '/tmp/testing-banner-file'],
    ],
    "thumb-file" => fn () => [
        'thumb-file' => ['tmp' => '/tmp/testing-thumb-file'],
    ],
    "thumb-half" => fn () => [
        'thumb-half' => ['tmp' => '/tmp/testing-thumb-half'],
    ],
    "any file" => fn() => [],
]);

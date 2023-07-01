<?php

use BRCas\CA\Domain\Exceptions\EntityNotFoundException;
use BRCas\CA\Domain\Exceptions\ValidationNotificationException;
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
use BRCas\MV\UseCases\Video\CreateVideoUseCase;
use BRCas\MV\UseCases\Video\DTO\CreateVideoInput;
use BRCas\MV\UseCases\Video\DTO\VideoOutput;
use BRCas\MV\UseCases\Video\Interfaces\VideoEventManagerInterface;

beforeEach(function () {
    $this->mockEntity = Mockery::mock(Video::class, [
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

    $this->mockEntity->shouldReceive('id')->andReturn((string) $id);
    $this->mockEntity->shouldReceive('createdAt')->andReturn($date->format('Y-m-d H:i:s'));

    $this->mockRepository = Mockery::mock(VideoRepositoryInterface::class);
    $this->mockRepository->shouldReceive('insert')->andReturn($this->mockEntity);

    $this->mockFileStorageInterface = Mockery::mock(FileStorageInterface::class);
    $this->mockFileStorageInterface->shouldReceive('store');

    $this->mockVideoEventManagerInterface = Mockery::mock(VideoEventManagerInterface::class);

    $this->mockCategoryRepositoryInterface = Mockery::mock(CategoryRepositoryInterface::class);
    $this->mockCastMemberRepositoryInterface = Mockery::mock(CastMemberRepositoryInterface::class);
    $this->mockGenreRepositoryInterface = Mockery::mock(GenreRepositoryInterface::class);
});

test("execute simple", function () {
    $this->mockCategoryRepositoryInterface->shouldReceive('getIdsByListId')->andReturn(mockKeyValue());
    $this->mockCastMemberRepositoryInterface->shouldReceive('getIdsByListId')->andReturn(mockKeyValue());
    $this->mockGenreRepositoryInterface->shouldReceive('getIdsByListId')->andReturn(mockKeyValue());

    $useCase = new CreateVideoUseCase(
        repository: $this->mockRepository,
        repositoryCategory: $this->mockCategoryRepositoryInterface,
        repositoryGenre: $this->mockGenreRepositoryInterface,
        repositoryCastMember: $this->mockCastMemberRepositoryInterface,
        transaction: mockTransaction(),
        storage: $this->mockFileStorageInterface,
        eventManager: $this->mockVideoEventManagerInterface,
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

    $this->mockRepository->shouldHaveReceived('insert')->times(1);
    $this->mockCategoryRepositoryInterface->shouldNotHaveReceived('getIdsByListId');
    $this->mockGenreRepositoryInterface->shouldNotHaveReceived('getIdsByListId');
    $this->mockCastMemberRepositoryInterface->shouldNotHaveReceived('getIdsByListId');
});

test("execute -> exception", function ($data) {
    $this->mockCategoryRepositoryInterface->shouldReceive('getIdsByListId')->andReturn(mockKeyValue());
    $this->mockCastMemberRepositoryInterface->shouldReceive('getIdsByListId')->andReturn(mockKeyValue());
    $this->mockGenreRepositoryInterface->shouldReceive('getIdsByListId')->andReturn(mockKeyValue());

    $useCase = new CreateVideoUseCase(
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
            input: new CreateVideoInput(
                title: array_key_exists('name', $data) ? $data['name'] : 'testing',
                description: $data['description'] ?? 'description',
                yearLaunched: 2010,
                duration: 50,
                opened: true,
                rating: 'L',
                categories: $data['categories'] ?? [],
                genres: $data['genres'] ?? [],
                castMembers: $data['cast-members'] ?? [],
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

    $useCase = new CreateVideoUseCase(
        repository: $this->mockRepository,
        repositoryCategory: $this->mockCategoryRepositoryInterface,
        repositoryGenre: $this->mockGenreRepositoryInterface,
        repositoryCastMember: $this->mockCastMemberRepositoryInterface,
        transaction: mockTransaction(),
        storage: $this->mockFileStorageInterface,
        eventManager: $this->mockVideoEventManagerInterface,
    );

    $response = $useCase->execute(
        input: new CreateVideoInput(
            title: array_key_exists('name', $data) ? $data['name'] : 'testing',
            description: $data['description'] ?? 'description',
            yearLaunched: 2010,
            duration: 50,
            opened: true,
            rating: 'L',
            categories: $data['categories'] ?? [],
            genres: $data['genres'] ?? [],
            castMembers: $data['cast-members'] ?? [],
        )
    );

    expect($response->categories)->toBe(array_values($data['categories'] ?? []));
    expect($response->genres)->toBe(array_values($data['genres'] ?? []));
    expect($response->cast_members)->toBe(array_values($data['cast-members'] ?? []));

    $this->mockRepository->shouldHaveReceived('insert')->times(1);
    
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

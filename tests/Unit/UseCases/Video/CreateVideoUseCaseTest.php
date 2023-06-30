<?php

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

$mockEntity = function(){
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

$mockCategoryRepository = function(){
    return Mockery::mock(CategoryRepositoryInterface::class);
};

$useCase = function () use ($mockRepository, $mockFileStorageInterface, $mockEventManagerInterface) {
    return new CreateVideoUseCase(
        repository: $mockRepository(),
        transaction: mockTransaction(),
        storage: $mockFileStorageInterface(),
        eventManager: $mockEventManagerInterface(),
    );
};

test("constructor", function () use($useCase){
    $useCase();
    expect(true)->toBeTrue();
});

test("execute", function () use($useCase){
    $response = $useCase()->execute(
        input: new CreateVideoInput(
            title: 'testing',
            description: 'description',
            yearLaunched: 2010,
            duration: 50,
            opened: true,
            rating: 'L',
            videoFile: ['path' => 'testing']
        )
    );

    expect($response)->toBeInstanceOf(VideoOutput::class);
    expect($response->title)->toBe('testing');
    expect($response->description)->toBe('description');
    expect($response->yearLaunched)->toBe(2010);
    expect($response->duration)->toBe(50);
    expect($response->opened)->toBe(true);
    expect($response->rating)->toBe('L');
    
    expect($response->id)->not->toBeEmpty();
    expect($response->created_at)->not->toBeEmpty();
});

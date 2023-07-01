<?php

use BRCas\CA\Domain\ValueObject\Uuid;
use BRCas\MV\Domain\Entity\Video;
use BRCas\MV\Domain\Enum\Rating;
use BRCas\MV\Domain\Repository\VideoRepositoryInterface;
use BRCas\MV\UseCases\Video\ListVideoUseCase;
use BRCas\MV\UseCases\Video\DTO\{VideoOutput, ListVideoInput};
use Ramsey\Uuid\Uuid as UuidUuid;

beforeEach(function(){
    $this->id = (string) UuidUuid::uuid4();
    $this->date = '2020-01-01 00:00:00';

    $this->entity = new Video(
        title: 'testing',
        description: 'testing',
        yearLaunched: 2010,
        duration: 50,
        opened: true,
        rating: Rating::L,
        thumbFile: null,
        bannerFile: null,
        trailerFile: null,
        videoFile: null,
        categories: [],
        genres: [],
        castMembers: [],
        publish: false,
        id: new Uuid($this->id),
        createdAt: new DateTime($this->date),
    );
    $this->mockRepository = Mockery::mock(VideoRepositoryInterface::class);
    $this->mockRepository->shouldReceive('getById')->andReturn($this->entity);
});

test("list a domain", function () {
    $useCase = new ListVideoUseCase(
        repository: $this->mockRepository,
    );
    
    $response = $useCase->execute(new ListVideoInput(id: 'testing'));
    expect($response)->toBeInstanceOf(VideoOutput::class);
    expect($response->title)->toBe('testing');
    expect($response->description)->toBe('testing');
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
});

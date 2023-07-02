<?php

use BRCas\CA\Domain\ValueObject\Uuid;
use BRCas\MV\Domain\Entity\Video;
use BRCas\MV\Domain\Enum\MediaStatus;
use BRCas\MV\Domain\Enum\Rating;
use BRCas\MV\Domain\Repository\VideoRepositoryInterface;
use BRCas\MV\Domain\ValueObject\Media;
use BRCas\MV\UseCases\Video\UpdatePathMediaUseCase;
use BRCas\MV\UseCases\Video\DTO\UpdatePathMediaInput;
use BRCas\MV\UseCases\Video\DTO\VideoOutput;

beforeEach(function () {
    $this->entity = new Video(
        id: $this->id = Uuid::make(),
        title: 'testing',
        description: 'description',
        yearLaunched: 2010,
        duration: 50,
        opened: true,
        rating: Rating::L,
        createdAt: new DateTime(),
    );

    $this->mockRepository = Mockery::mock(VideoRepositoryInterface::class);
});

test("I'm trying update a video without a media file", function () {
    $this->mockRepository->shouldReceive('getById')->andReturn($this->entity);
    $this->mockRepository->shouldReceive('updateMedia')->andReturn($this->entity);

    $useCase = new UpdatePathMediaUseCase(
        repository: $this->mockRepository,
    );

    $response = $useCase->execute(new UpdatePathMediaInput(
        id: $this->id,
        path: 'testing',
        type: 'video'
    ));

    expect($response)->toBeInstanceOf(VideoOutput::class);

    $this->mockRepository->shouldNotHaveReceived('updateMedia');
});

test("I'm trying update a video with a media file", function () {
    $this->entity->setVideoFile(new Media(path: 'test', status: MediaStatus::PENDING));

    $this->mockRepository->shouldReceive('getById')->andReturn($this->entity);
    $this->mockRepository->shouldReceive('updateMedia')->andReturn($this->entity);

    $useCase = new UpdatePathMediaUseCase(
        repository: $this->mockRepository,
    );

    $response = $useCase->execute(new UpdatePathMediaInput(
        id: $this->id,
        path: 'testing',
        type: 'video'
    ));

    expect($response)->toBeInstanceOf(VideoOutput::class);

    $this->mockRepository->shouldHaveReceived('updateMedia')->times(1);
});

test("I'm trying update a trailer without a media file", function () {
    $this->mockRepository->shouldReceive('getById')->andReturn($this->entity);
    $this->mockRepository->shouldReceive('updateMedia')->andReturn($this->entity);

    $useCase = new UpdatePathMediaUseCase(
        repository: $this->mockRepository,
    );

    $response = $useCase->execute(new UpdatePathMediaInput(
        id: $this->id,
        path: 'testing',
        type: 'video'
    ));

    expect($response)->toBeInstanceOf(VideoOutput::class);

    $this->mockRepository->shouldNotHaveReceived('updateMedia');
});

test("I'm trying update a trailer with a media file", function () {
    $this->entity->setTrailerFile(new Media(path: 'test', status: MediaStatus::PENDING));

    $this->mockRepository->shouldReceive('getById')->andReturn($this->entity);
    $this->mockRepository->shouldReceive('updateMedia')->andReturn($this->entity);

    $useCase = new UpdatePathMediaUseCase(
        repository: $this->mockRepository,
    );

    $response = $useCase->execute(new UpdatePathMediaInput(
        id: $this->id,
        path: 'testing',
        type: 'trailer'
    ));

    expect($response)->toBeInstanceOf(VideoOutput::class);

    $this->mockRepository->shouldHaveReceived('updateMedia')->times(1);
});

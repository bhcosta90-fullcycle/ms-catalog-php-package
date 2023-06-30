<?php

use BRCas\CA\Domain\Events\DTO\PayloadEventOutputInterface;
use BRCas\MV\Domain\Entity\Video;
use BRCas\MV\Domain\Enum\MediaStatus;
use BRCas\MV\Domain\Enum\Rating;
use BRCas\MV\Domain\Event\DTO\VideoCreateEvent as DTOVideoCreateEvent;
use BRCas\MV\Domain\Event\VideoCreateEvent;
use BRCas\MV\Domain\ValueObject\Media;

beforeEach(function () {
    $this->video = new Video(
        title: 'testing',
        description: 'testing',
        yearLaunched: 2010,
        duration: 50,
        opened: true,
        rating: Rating::L,
        videoFile: new Media('testing/testing.mp4', MediaStatus::PENDING),
    );
});

test("validate a name of event", function () {
    $event = new VideoCreateEvent($this->video);
    expect($event->name())->toBe("video.create." . $this->video->id());
});

test("validate a payload of event", function () {
    $event = new VideoCreateEvent($this->video);
    $payload = $event->payload();
    expect($payload)->toBeInstanceOf(DTOVideoCreateEvent::class);
    expect($payload)->toBeInstanceOf(PayloadEventOutputInterface::class);
    expect($payload->id)->toBe($this->video->id());
    expect($payload->path)->toBe($this->video->videoFile()->path);
});

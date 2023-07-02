<?php

use BRCas\MV\Domain\Builder\Video\CreateBuilderVideo;

beforeEach(function(){
    $this->builder = new CreateBuilderVideo();

    $this->stdClass = new stdClass();
    $this->stdClass->rating = "L";
    $this->stdClass->title = "testing";
    $this->stdClass->description = "testing";
    $this->stdClass->yearLaunched = 2010;
    $this->stdClass->duration = 50;
    $this->stdClass->opened = true;
});

test("verify if there is a category in video", function () {
    $this->stdClass->categories = ['123', '456'];
    $this->stdClass->genres = ['123', '456'];
    $this->stdClass->castMembers = ['123', '456'];
    $this->builder->createEntity($this->stdClass);
    $this->builder->addIds($this->stdClass);
    
    $input = new stdClass();
    $input->categories = ["789"];
    $input->genres = ["987"];
    $input->castMembers = ["654"];

    $this->builder->addIds($input);
    expect($this->builder->getEntity()->categories)->toBe($input->categories);
    expect($this->builder->getEntity()->genres)->toBe($input->genres);
    expect($this->builder->getEntity()->castMembers)->toBe($input->castMembers);
});

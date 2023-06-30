<?php

use BRCas\CA\Domain\Notification\ValidationNotification;

test('get errors', function () {
    $notification = new ValidationNotification();
    $errors = $notification->getErrors();
    expect($errors)->toBeArray();
});

test('add errors', function () {
    $notification = new ValidationNotification();
    $notification->addError('video', 'video is required');
    expect($notification->getErrors())->toHaveCount(1);
});

test('has erros', function () {
    $notification = new ValidationNotification();
    expect($notification->hasError())->toBeFalse();
    $notification->addError('video', 'video is required');
    expect($notification->hasError())->toBeTrue();
});

test('messages', function () {
    $notification = new ValidationNotification();
    $notification->addError('video', 'video is required 01');
    $notification->addError('video', 'video is required 02');
    expect($notification->messages())->toBeString();
    expect($notification->messages())->toBe('video: video is required 01, video: video is required 02');
});

test('messages with filter context', function () {
    $notification = new ValidationNotification();
    $notification->addError('video', 'video is required');
    $notification->addError('category', 'category is required');
    expect($notification->messages('video'))->toBe('video: video is required');
});

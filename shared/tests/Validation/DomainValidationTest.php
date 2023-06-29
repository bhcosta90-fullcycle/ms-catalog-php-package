<?php

use BRCas\CA\Domain\Exceptions\EntityValidationException;
use BRCas\CA\Domain\Validation\DomainValidation;

test("not null", function () {
    DomainValidation::make('')->notNull();
})->throws(EntityValidationException::class, 'This value is null');

test("not null with message", function () {
    DomainValidation::make('')->notNull('Custom message');
})->throws(EntityValidationException::class, 'Custom message');

test("string max length", function () {
    DomainValidation::make(str_repeat('a', 6))->strMaxLength(5);
})->throws(EntityValidationException::class, 'This value must not be greater than 5 characters');

test("string max length with message", function () {
    DomainValidation::make(str_repeat('a', 6))->strMaxLength(5, 'Custom message');
})->throws(EntityValidationException::class, 'Custom message');

test("string can be null and max length", function () {
    DomainValidation::make(str_repeat('a', 6))->strCanNullMaxLength(5);
})->throws(EntityValidationException::class, 'This value must not be greater than 5 characters');

test("string min length", function () {
    DomainValidation::make(str_repeat('a', 7))->strMinLength(8);
})->throws(EntityValidationException::class, 'This value must at least 8 characters');

test("string min length with message", function () {
    DomainValidation::make(str_repeat('a', 7))->strMinLength(8, 'Custom message');
})->throws(EntityValidationException::class, 'Custom message');

test("string can be null and max length with message", function () {
    DomainValidation::make(str_repeat('a', 6))->strCanNullMaxLength(5, 'Custom message');
})->throws(EntityValidationException::class, 'Custom message');

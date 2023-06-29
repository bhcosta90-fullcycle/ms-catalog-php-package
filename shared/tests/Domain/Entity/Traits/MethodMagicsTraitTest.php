<?php

use BRCas\CA\Domain\Traits\MethodMagicsTrait;

class StubMethodMagicsTraitTest
{
    use MethodMagicsTrait;

    public function __construct(
        protected string $name,
    ) {
        //
    }
}

test("test trait with parameter valid", function () {
    $class = new StubMethodMagicsTraitTest(name: 'testing');
    expect($class->name)->toBe('testing');
});

test("test trait with parameter invalid", function () {
    (new StubMethodMagicsTraitTest(name: 'testing'))->cnpj;
})->throws(Exception::class);

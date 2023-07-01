<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

// uses(Tests\TestCase::class)->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

use BRCas\CA\Repository\ItemInterface;
use BRCas\CA\Repository\KeyValueInterface;
use BRCas\CA\Repository\PaginateInterface;
use BRCas\CA\UseCase\DatabaseTransactionInterface;

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function mockPaginate($items = []): PaginateInterface
{
    $mockItem = Mockery::mock(PaginateInterface::class);
    $mockItem->shouldReceive('items')->andReturn($items);
    $mockItem->shouldReceive('total')->andReturn(0);
    $mockItem->shouldReceive('lastPage')->andReturn(0);
    $mockItem->shouldReceive('firstPage')->andReturn(0);
    $mockItem->shouldReceive('currentPage')->andReturn(0);
    $mockItem->shouldReceive('perPage')->andReturn(0);
    $mockItem->shouldReceive('to')->andReturn(0);
    $mockItem->shouldReceive('from')->andReturn(0);

    return $mockItem;
}

function mockItem($items = []): ItemInterface
{
    $mockItem = Mockery::mock(ItemInterface::class);
    $mockItem->shouldReceive('items')->andReturn($items);
    $mockItem->shouldReceive('total')->andReturn(count($items));
    return $mockItem;
}

function mockKeyValue($items = []): KeyValueInterface
{
    $newItem = [];

    foreach($items as $item) {
        $newItem[$item] = $item;
    }

    $mockItem = Mockery::mock(KeyValueInterface::class);
    $mockItem->shouldReceive('items')->andReturn($newItem);
    return $mockItem;
}

function mockTransaction(): DatabaseTransactionInterface{
    $mock = Mockery::mock(DatabaseTransactionInterface::class);
    $mock->shouldReceive('commit')->shouldReceive('rollback');

    return $mock;
}

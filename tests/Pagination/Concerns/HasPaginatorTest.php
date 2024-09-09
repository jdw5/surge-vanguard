<?php

use Conquest\Table\Pagination\Enums\Paginator;
use Conquest\Table\Table;
use Conquest\Table\Tests\Pagination\Concerns\Classes\MethodTable;
use Conquest\Table\Tests\Pagination\Concerns\Classes\PropertyTable;

it('uses paginator default as simple', function () {
    expect(Table::make()->getPaginator())->toBe(Paginator::Simple);
});

it('can set a paginator', function () {
    $table = Table::make();
    $table->setPaginator(Paginator::Cursor);
    expect($table->getPaginator())->toBe(Paginator::Cursor);
});

it('uses paginator attribute', function () {
    expect(PropertyTable::make()->getPaginator())->toBe(Paginator::Cursor);
});

it('uses paginator method', function () {
    expect(MethodTable::make()->getPaginator())->toBe(Paginator::Cursor);
});

it('prevents setting invalid paginator', function () {
    $table = Table::make();
    expect(function () use ($table) {
        $table->setPaginator('invalid');
    })->toThrow(ValueError::class);
});

it('allows setting all valid paginators', function () {
    $table = Table::make();

    $table->setPaginator(Paginator::None);
    expect($table->getPaginator())->toBe(Paginator::None);

    $table->setPaginator(Paginator::Simple);
    expect($table->getPaginator())->toBe(Paginator::Simple);

    $table->setPaginator(Paginator::Cursor);
    expect($table->getPaginator())->toBe(Paginator::Cursor);

});

it('defaults to simple if null', function () {
    $table = Table::make();
    $table->setPaginator(null);
    expect($table->getPaginator())->toBe(Paginator::Simple);
});

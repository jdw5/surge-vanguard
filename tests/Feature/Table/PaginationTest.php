<?php

use Conquest\Table\Table;
use Workbench\App\Models\Product;
use Conquest\Table\Columns\Column;
use Conquest\Table\Pagination\Pagination;
use Conquest\Table\Pagination\Enums\PaginationType;

beforeEach(function () {
    Table::setGlobalDefaultPagination(10);
    Table::setGlobalPageTerm('page');
    Table::setGlobalShowKey('show');
});

it('paginates by default number', function () {
    $table = Table::make(
        resource: Product::query(),
        columns: [
            Column::make('id')->key(),
            Column::make('name')
        ],
    );
    expect($table->getPagination())->toBe(10);
    expect($table->getTableRecords())->toHaveCount(10);
    expect($table->getPaginateType())->toBe(PaginationType::SIMPLE);
});

it('paginates by provided number', function () {
    $table = Table::make(
        resource: Product::query(),
        columns: [
            Column::make('id')->key(),
            Column::make('name')
        ],
        pagination: 20,
    );
    expect($table->getPagination())->toBe(20);
    expect($table->getTableRecords())->toHaveCount(20);
    expect($table->getPaginateType())->toBe(PaginationType::SIMPLE);
});

it('can change the default pagination amount', function () {
    Table::setGlobalDefaultPagination(20);
    $table = Table::make(
        resource: Product::query(),
        columns: [
            Column::make('id')->key(),
            Column::make('name')
        ],
    );
    expect($table->getPagination())->toBe(20);
    expect($table->getTableRecords())->toHaveCount(20);
    expect($table->getPaginateType())->toBe(PaginationType::SIMPLE);
});

it('can change the global page term', function () {
    Table::setGlobalPageTerm('p');
    $table = Table::make(
        resource: Product::query(),
        columns: [
            Column::make('id')->key(),
            Column::make('name')
        ],
    );
    expect($table->getPageTerm())->toBe('p');
    expect($table->getPaginateType())->toBe(PaginationType::SIMPLE);
});

it('can have dynamic pagination', function () {
    $table = Table::make(
        resource: Product::query(),
        columns: [
            Column::make('id')->key(),
            Column::make('name')
        ],
        pagination: [5, 10, 20]
    );
    expect($table->getPagination())->toBe([5, 10, 20]);
    expect($table->getTableRecords())->toHaveCount(10);
    expect($table->getPaginateType())->toBe(PaginationType::SIMPLE);
});

it('create pagination options', function () {
    $table = Table::make(
        resource: Product::query(),
        columns: [
            Column::make('id')->key(),
            Column::make('name')
        ],
        pagination: [5, 10, 20]
    );
    expect($table->getPaginationOptions())->toHaveCount(3)
        ->each->toBeInstanceOf(Pagination::class);
});

it('can change pagination type', function () {
    $table = Table::make(
        resource: Product::query(),
        columns: [
            Column::make('id')->key(),
            Column::make('name')
        ],
        pagination: [5, 10, 20],
    );
    $table->setPaginationType('cursor');
    expect($table->getPaginateType())->toBe(PaginationType::CURSOR);
});

it('can change show key globally', function () {
    Table::setGlobalShowKey('s');

    $table = Table::make(
        resource: Product::query(),
        columns: [
            Column::make('id')->key(),
            Column::make('name')
        ],
    );
    expect($table->getShowKey())->toBe('s');
});

it('changes pagination amount based on the show key', function () {
    Table::setGlobalShowKey('s');

    $table = Table::make(
        resource: Product::query(),
        columns: [
            Column::make('id')->key(),
            Column::make('name')
        ],
        pagination: [5, 10, 20],
    );
    request()->merge(['s' => 20]);
    expect($table->getPagination())->toBe([5, 10, 20]);
    expect($table->getTableRecords())->toHaveCount(20);
});

it('prevents changing the pagination amount if key outside array', function () {
    Table::setGlobalShowKey('s');

    $table = Table::make(
        resource: Product::query(),
        columns: [
            Column::make('id')->key(),
            Column::make('name')
        ],
        pagination: [5, 10, 20],
    );
    request()->merge(['s' => 100]);
    expect($table->getPagination())->toBe([5, 10, 20]);
    expect($table->getTableRecords())->toHaveCount($table->getDefaultPagination());
});
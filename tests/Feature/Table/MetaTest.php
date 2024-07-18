<?php

use Conquest\Table\Table;
use Workbench\App\Models\Product;
use Conquest\Table\Columns\Column;
use Conquest\Table\Pagination\Enums\PaginationType;

beforeEach(function () {
    Table::setGlobalPageTerm('page');
    Table::setGlobalShowKey('show');
});

it('uses simple pagination by default', function () {
    $table = Table::make(
        resource: Product::query(),
        columns: [
            Column::make('id')->key(),
        ],
    );
    expect($table->getPaginateType())->toBe(PaginationType::SIMPLE);
    expect($t = $table->getTableMeta())->toHaveCount(13);
    expect($t['empty'])->toBeFalse();
    expect($t['first_url'])->toBe(url('?page=1'));
    expect($t['per_page'])->toBe($table->getPagination());
});

it('uses set term for pagination pagination by default', function () {
    $table = Table::make(
        resource: Product::query(),
        columns: [
            Column::make('id')->key(),
        ],
    );
    $table->setPageTerm('p');
    expect($table->getPaginateType())->toBe(PaginationType::SIMPLE);
    expect($t = $table->getTableMeta())->toHaveCount(13);
    expect($t['empty'])->toBeFalse();
    expect($t['first_url'])->toBe(url('?p=1'));
    expect($t['per_page'])->toBe($table->getPagination());
});

it('can be cursor paginated', function () {
    $table = Table::make(
        resource: Product::query(),
        columns: [
            Column::make('id')->key(),
        ],
    );
    $table->setPaginationType(PaginationType::CURSOR);
    $table->setPageTerm('c');
    expect($table->getPaginateType())->toBe(PaginationType::CURSOR);
    expect($t = $table->getTableMeta())->toHaveCount(7);
    expect($t['empty'])->toBeFalse();
    expect($t['per_page'])->toBe($table->getPagination());
    expect(explode('=', $t['next_url'])[0])->toBe(url('?c'));
});

it('can have no pagination', function () {
    $table = Table::make(
        resource: Product::query(),
        columns: [
            Column::make('id')->key(),
        ],
    );
    $table->setPaginationType(PaginationType::NONE);
    expect($table->getPaginateType())->toBe(PaginationType::NONE);
    expect($t = $table->getTableMeta())->toHaveCount(2);
    expect($t['empty'])->toBeFalse();
    expect($t['show'])->toBeTrue();
});

it('changes the pagination amount if dynamic', function () {
    $table = Table::make(
        resource: Product::query(),
        columns: [
            Column::make('id')->key(),
        ],
        pagination: [5, 10, 20]
    );
    request()->merge(['show' => 20]);
    expect($table->getPagination())->toBe([5, 10, 20]);
    expect($t = $table->getTableMeta())->toHaveCount(13);
    expect($t['per_page'])->toBe(20);
    expect($table->getTableRecords())->toHaveCount(20);
});

it('defaults to 10 if pagination amount is dynamic but invalid', function () {
    $table = Table::make(
        resource: Product::query(),
        columns: [
            Column::make('id')->key(),
        ],
    );
    $table->setPagination([5, 10, 20]);
    request()->merge(['show' => 30, 'page' => 2]);
    expect($table->getPagination())->toBe([5, 10, 20]);
    expect($t = $table->getTableMeta())->toHaveCount(13);
    expect($t['per_page'])->toBe(10);
});

it('updates the future pages', function () {
    $table = Table::make(
        resource: Product::query(),
        columns: [
            Column::make('id')->key(),
        ],
        pagination: [5, 10, 20]
    );
    request()->merge(['page' => 5]);
    expect($table->getPagination())->toBe([5, 10, 20]);
    expect($t = $table->getTableMeta())->toHaveCount(13);
    expect($t['next_url'])->toBe(url('?page=6'));
    expect($t['prev_url'])->toBe(url('?page=4'));
});
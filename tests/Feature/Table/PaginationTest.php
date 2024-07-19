<?php

use Conquest\Table\Table;
use Workbench\App\Models\Product;
use Conquest\Table\Columns\Column;
use Conquest\Table\Pagination\Pagination;
use Conquest\Table\Pagination\Enums\PaginationType;
use Illuminate\Support\Facades\Request;

beforeEach(function () {
    $this->table = Table::make(
        resource: Product::query(),
        columns: [
            Column::make('id')->key(),
        ],
    );
});

it('paginates by config defaults', function () {
    expect($this->table->getPaginationCount())->toBe(config('table.pagination.default'));
    expect($this->table->getTableRecords())->toHaveCount(config('table.pagination.default'));
    expect($this->table->getPaginateType())->toBe(PaginationType::SIMPLE);
});

it('can change default pagination', function () {
    $this->table->setDefaultPerPage(20);
    expect($this->table->getPaginationCount())->toBe(20);
    expect($this->table->getTableRecords())->toHaveCount(20);
});

it('can change the pagination amount', function () {
    $this->table->setPerPage(15);
    $this->table->setDefaultPerPage(20);
    expect($this->table->getPaginationCount())->toBe(15);
    expect($this->table->getTableRecords())->toHaveCount(15);
});

it('can change the page name', function () {
    $this->table->setPageName('p');
    expect($this->table->getPageName())->toBe('p');
});

it('can use an array for pagination', function () {
    $this->table->setPagination([5, 10, 20]);
    expect($this->table->getPaginationCount())->toBe([5, 10, 20]);
    expect($this->table->getTableRecords())->toHaveCount(10);
});

it('creates pagination options', function () {
    $this->table->setPerPage([5, 10, 20]);
    expect($this->table->getPagination())->toHaveCount(3)
        ->each->toBeInstanceOf(Pagination::class);
});

it('can change pagination type', function () {
    $this->table->setPaginationType('cursor');
    expect($this->table->getPaginateType())->toBe(PaginationType::CURSOR);
});

it('can change show key', function () {
    $this->table->setShowKey('s');
    expect($this->table->getShowKey())->toBe('s');
});

it('changes pagination amount based on the show key', function () {
    $this->table->setPerPage([5, 10, 20]);
    $this->table->setShowKey('s');
    Request::merge(['s' => 20]);
    expect($this->table->getPaginationCount())->toBe([5, 10, 20]);
    expect($this->table->getTableRecords())->toHaveCount(20);
});

it('prevents changing the pagination amount if key outside array', function () {
    $this->table->setPerPage([5, 10, 20]);
    Request::merge(['s' => 100]);
    expect($this->table->getPaginationCount())->toBe([5, 10, 20]);
    expect($this->table->getTableRecords())->toHaveCount($this->table->getDefaultPerPage());
});
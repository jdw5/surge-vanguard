<?php

use Conquest\Table\Columns\Column;
use Conquest\Table\Pagination\Enums\PaginationType;
use Conquest\Table\Table;
use Illuminate\Support\Facades\Request;
use Workbench\App\Models\Product;

beforeEach(function () {
    $this->table = Table::make(
        resource: Product::query(),
        columns: [
            Column::make('id')->key(),
        ],
    );
});

it('uses simple pagination by default', function () {
    expect($this->table->getPaginateType())->toBe(PaginationType::SIMPLE);
    expect($t = $this->table->getTableMeta())->toHaveCount(13);
    expect($t['empty'])->toBeFalse();
    expect($t['first_url'])->toBe(url('?page=1'));
    expect($t['per_page'])->toBe($this->table->getPaginationCount());
});

it('uses set term for pagination pagination by default', function () {
    $this->table->setPageName('p');
    expect($this->table->getPaginateType())->toBe(PaginationType::SIMPLE);
    expect($t = $this->table->getTableMeta())->toHaveCount(13);
    expect($t['empty'])->toBeFalse();
    expect($t['first_url'])->toBe(url('?p=1'));
    expect($t['per_page'])->toBe($this->table->getPaginationCount());
});

it('can be cursor paginated', function () {
    $this->table->setPaginationType(PaginationType::CURSOR);
    $this->table->setPageName('c');
    expect($this->table->getPaginateType())->toBe(PaginationType::CURSOR);
    expect($t = $this->table->getTableMeta())->toHaveCount(7);
    expect($t['empty'])->toBeFalse();
    expect($t['per_page'])->toBe($this->table->getPaginationCount());
    expect(explode('=', $t['next_url'])[0])->toBe(url('?c'));
});

it('can have no pagination', function () {
    $this->table->setPaginationType(PaginationType::NONE);
    expect($this->table->getPaginateType())->toBe(PaginationType::NONE);
    expect($t = $this->table->getTableMeta())->toHaveCount(2);
    expect($t['empty'])->toBeFalse();
    expect($t['show'])->toBeTrue();
});

it('changes the pagination amount if dynamic', function () {
    $this->table->setPagination([5, 10, 20]);
    Request::merge(['show' => 20]);
    expect($this->table->getPaginationCount())->toBe([5, 10, 20]);
    expect($t = $this->table->getTableMeta())->toHaveCount(13);
    expect($t['per_page'])->toBe(20);
    expect($this->table->getTableRecords())->toHaveCount(20);
});

it('defaults to 10 if pagination amount is dynamic but invalid', function () {
    $this->table->setPagination([5, 10, 20]);
    Request::merge(['show' => 30, 'page' => 2]);
    expect($this->table->getPaginationCount())->toBe([5, 10, 20]);
    expect($t = $this->table->getTableMeta())->toHaveCount(13);
    expect($t['per_page'])->toBe(10);
});

it('updates the future pages', function () {
    $this->table->setPagination([5, 10, 20]);
    Request::merge(['page' => 5]);
    expect($this->table->getPaginationCount())->toBe([5, 10, 20]);
    expect($t = $this->table->getTableMeta())->toHaveCount(13);
    expect($t['next_url'])->toBe(url('?page=6'));
    expect($t['prev_url'])->toBe(url('?page=4'));
});

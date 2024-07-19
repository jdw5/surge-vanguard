<?php

use Conquest\Table\Table;
use Workbench\App\Models\Product;
use Conquest\Table\Columns\Column;
use Illuminate\Support\Facades\Request;

beforeEach(function () {
    $this->table = Table::make(
        resource: Product::query(),
        columns: [
            Column::make('id')->key(),
            Column::make('name'),
        ],
    );
});
it('uses config defaults for search', function () {
    expect($this->table)
        ->getSearchKey()->toBe('q')
        ->hasSearch()->toBeFalse()
        ->usesScout()->toBeFalse();
});

it('can change search key', function () {
    $this->table->setSearchKey('search');
    expect($this->table)
        ->getSearchKey()->toBe('search');
});

it('can set search', function () {
    $this->table->setSearch('name');
    expect($this->table->getSearch())->toBe('name');
});

it('can set search to array', function () {
    $this->table->setSearch(['name', 'id']);
    expect($this->table->getSearch())->toHaveCount(2);
});

it('can use scout', function () {
    $this->table->setScout(true);
    expect($this->table->usesScout())->toBeTrue();
});

it('can retrieve a query string search term', function () {
    Request::merge(['q' => 'search term']);
    expect($this->table->getSearchFromRequest())->toBe('search term');
});

it('allows for empty query string', function () {
    expect($this->table->getSearchFromRequest())->toBeNull();
});

it('prevents searching if not enabled', function () {
    $this->table->search($q = Product::query());
    expect($q->toSql())->not->toContain('where');
});

it('can search on the database', function () {
    $this->table->setSearch(['name', 'description']);
    Product::factory()->create([
        'name' => 'search term',
        'category_id' => 1,
    ]);
    Request::merge(['q' => 'search term']);
    $this->table->search($q = Product::query());
    expect($q->toSql())->toContain('where')
        ->toContain('or');
    expect($q->get())->toHaveCount(1);
});

// Scout tests
<?php

use Illuminate\Database\Eloquent\Builder;
use Workbench\App\Models\Product;
use Workbench\App\Tables\ProductTable;

beforeEach(function () {
    $this->table = ProductTable::make();
});

it('has a key', function () {
    expect($this->table->getTableKey())->toBe('id');
});

it('has a resource', function () {
    expect($this->table->getResource())->toBeInstanceOf(Builder::class);
    expect($this->table->getModelClass())->toBe(Product::class);
});

it('has columns', function () {
    expect($this->table->getTableColumns())->toHaveCount(7);
});

it('has filters', function () {
    expect($this->table->getFilters())->toHaveCount(3);
});

it('has sorts', function () {
    expect($this->table->getSorts())->toHaveCount(2);
});

it('has actions', function () {
    expect($this->table->getActions())->toHaveCount(3);
    expect($this->table->getInlineActions())->toHaveCount(1);
    expect($this->table->getPageActions())->toHaveCount(0);
    expect($this->table->getBulkActions())->toHaveCount(2);
    expect($this->table->getDefaultAction())->toBeNull();
});

it('has array form', function () {
    expect($this->table->toArray())->toHaveKeys([
        'records',
        'headings',
        'meta',
        'sorts',
        'filters',
        'columns',
        'pagination',
        'actions',
        'keys']);

});

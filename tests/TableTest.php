<?php

use Workbench\App\Tables\ProductTable;
use function Pest\Laravel\get;

beforeAll(function () {
    $GLOBALS['table'] = ProductTable::make();
});

it('can be made', function () {
    expect($GLOBALS['table'])->not->toBeNull();
    $alias = ProductTable::build();
    expect($alias)->not->toBeNull();
});

// it('is serialized', function () {
//     $table = $GLOBALS['table'];
//     get('/products')->assertOk()->assertSee($table->getTableKey());
// });

it('has columns', function () {
    $table = $GLOBALS['table'];
    $columns = $table->getTableColumns();
    expect($columns)->toHaveCount(7);
});

it('has a key', function () {
    $table = $GLOBALS['table'];
    $key = $table->getTableKey();
    expect($key)->toBe('public_id');
});

it('has records', function () {
    $table = $GLOBALS['table'];
    $records = $table->getTableRecords();
    expect($records)->toHaveCount($table->getDefaultPagination());
});

it('has filters', function () {
    $table = $GLOBALS['table'];
    $filters = $table->getFilters();
    expect($filters)->toHaveCount(3);
});

it('has sorts', function () {
    $table = $GLOBALS['table'];
    $sorts = $table->getSorts();
    expect($sorts)->toHaveCount(2);
});

it('has actions', function () {
    $table = $GLOBALS['table'];
    $actions = $table->getActions();
    expect($actions)->toHaveCount(0);
});
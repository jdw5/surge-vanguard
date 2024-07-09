<?php

use Workbench\App\Tables\ProductTable;

use function Pest\Laravel\get;

beforeAll(function () {
    $GLOBALS['table'] = ProductTable::make();
});

it('can be made', function () {
    expect($GLOBALS['table'])->not->toBeNull();
});

it('is serialized', function () {
    $table = $GLOBALS['table'];
    get('/products')->assertOk()->assertSee($table->getTableKey());
});

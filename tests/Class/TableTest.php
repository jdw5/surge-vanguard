<?php

use Workbench\App\Models\Product;
use Workbench\App\Tables\ProductTable;
use function Pest\Laravel\get;


it('can make a table from class', function () {
    $alias = ProductTable::build();
    expect($alias)->not->toBeNull();
});

// it('is serialized', function () {
//     $table = $GLOBALS['table'];
//     get('/products')->assertOk()->assertSee($table->getTableKey());
// });

// it('has columns', function () {
//     $table = $GLOBALS['table'];
//     $columns = $table->getTableColumns();
//     expect($columns)->toHaveCount(7);
// });

// it('has a key', function () {
//     $table = $GLOBALS['table'];
//     $key = $table->getTableKey();
//     expect($key)->toBe('public_id');
// });

// it('has records', function () {
//     $records = $this->table->getTableRecords();
//     expect($records)->toHaveCount($this->table->getDefaultPagination());
// });

// it('has filters', function () {
//     $table = $GLOBALS['table'];
//     $filters = $table->getFilters();
//     expect($filters)->toHaveCount(3);
// });

// it('has sorts', function () {
//     $table = $GLOBALS['table'];
//     $sorts = $table->getSorts();
//     expect($sorts)->toHaveCount(2);
// });

// it('has actions', function () {
//     $table = $GLOBALS['table'];
//     $actions = $table->getActions();
//     expect($actions)->toHaveCount(0);
// });
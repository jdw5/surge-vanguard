<?php

use Conquest\Table\Table;
use Workbench\App\Models\Product;
use Conquest\Table\Columns\Column;
use Workbench\App\Tables\ProductTable;

it('tests', function () {
    $table = ProductTable::make(
        resource: Product::query(),
        columns: [
            Column::make('id')->key(),
        ],
    );

    dd(str(class_basename($table))->snake());
});
<?php

use Workbench\App\Tables\ProductTable;

it('handles inline action', function () {
    $request = new \Illuminate\Http\Request([
        'name' => 'edit',
        'type' => 'action:inline',
        'id' => 1,
    ]);

    ProductTable::make()->handle($request);
});
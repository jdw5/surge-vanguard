<?php

use Illuminate\Http\Request;
use Workbench\App\Models\Product;
use Workbench\App\Tables\ProductTable;

it('why', function () {
    expect(true)->toBeTrue();
});

it('handles inline action', function () {
    $request = new Request([
        'name' => 'edit',
        'type' => 'action:inline',
        'id' => 1,
    ]);

    ProductTable::make()->handle($request);
    expect(Product::find(1)->name)->toBe('Inline');
});

it('handles bulk action', function () {
    $request = new Request([
        'name' => 'edit',
        'type' => 'action:bulk',
        'except' => [],
        'only' => [1, 2, 3],
        'all' => false,
    ]);
    ProductTable::make()->handle($request);
    expect(Product::find(1)->name)->toBe('Bulk');
    expect(Product::find(2)->name)->toBe('Bulk');
    expect(Product::find(3)->name)->toBe('Bulk');
    expect(Product::find(4)->name)->not->toBe('Bulk');
});

it('handles all exception records', function () {
    $request = new Request([
        'name' => 'mass',
        'type' => 'action:bulk',
        'except' => [1],
        'only' => [],
        'all' => true,
    ]);
    ProductTable::make()->handle($request);
    expect(Product::find(1)->name)->not->toBe('All');
    expect(Product::whereNot('id', 1)->pluck('name'))->each->toBe('All');
});

it('handles all records', function () {
    $request = new Request([
        'name' => 'mass',
        'type' => 'action:bulk',
        'except' => [],
        'only' => [],
        'all' => true,
    ]);
    ProductTable::make()->handle($request);
    expect(Product::pluck('name'))->each->toBe('All');
});
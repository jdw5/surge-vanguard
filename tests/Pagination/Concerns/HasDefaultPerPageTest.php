<?php

use Conquest\Table\Table;
use Conquest\Table\Tests\Pagination\Concerns\Classes\MethodTable;
use Conquest\Table\Tests\Pagination\Concerns\Classes\PropertyTable;

it('uses default per page default as null', function () {
    expect(Table::make()->getDefaultPerPage())->toBe(config('table.pagination.default'));
});

it('can set a default per page', function () {
    $table = Table::make();
    $table->setDefaultPerPage(20);
    expect($table->getDefaultPerPage())->toBe(20);
});

it('uses default per page attribute', function () {
    expect(PropertyTable::make()->getDefaultPerPage())->toBe(20);
});

it('uses default per page method', function () {
    expect(MethodTable::make()->getDefaultPerPage())->toBe(20);
});

it('prevents setting default per page as null', function () {
    $table = Table::make();
    $table->setDefaultPerPage(null);
    expect($table->getDefaultPerPage())->toBe(config('table.pagination.default'));
});

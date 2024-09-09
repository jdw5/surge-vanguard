<?php

use Conquest\Table\Table;
use Conquest\Table\Tests\Pagination\Concerns\Classes\MethodTable;
use Conquest\Table\Tests\Pagination\Concerns\Classes\PropertyTable;

it('uses page name default as config', function () {
    expect(Table::make()->getPageName())->toBe(config('table.pagination.name'));
});

it('can set a page name', function () {
    $table = Table::make();
    $table->setPageName('p');
    expect($table->getPageName())->toBe('p');
});

it('uses page name attribute', function () {
    expect(PropertyTable::make()->getPageName())->toBe('p');
});

it('uses page name method', function () {
    expect(MethodTable::make()->getPageName())->toBe('p');
});

it('prevents setting page name as null', function () {
    $table = Table::make();
    $table->setPageName(null);
    expect($table->getPageName())->toBe(config('table.pagination.name'));
});

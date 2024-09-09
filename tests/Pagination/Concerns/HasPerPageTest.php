<?php

use Conquest\Table\Table;
use Conquest\Table\Tests\Pagination\Concerns\Classes\MethodTable;
use Conquest\Table\Tests\Pagination\Concerns\Classes\PropertyTable;

it('uses per page default as null', function () {
    expect(Table::make()->getPerPage())->toBeNull();
});

it('can set a per page', function () {

    $table = Table::make();
    $table->setPerPage(20);
    expect($table->getPerPage())->toBe(20);
});

it('uses per page attribute', function () {
    expect(PropertyTable::make()->getPerPage())->toBe(20);
});

it('uses per page method', function () {
    expect(MethodTable::make()->getPerPage())->toBe(20);
});

it('prevents setting per page as null', function () {
    $table = Table::make();
    $table->setPerPage(null);
    expect($table->getPerPage())->toBeNull();
});

it('allows for per page to be array', function () {
    $table = Table::make();
    $table->setPerPage([10, 20, 30]);
    expect($table->getPerPage())->toBe([10, 20, 30]);
});

<?php

use Conquest\Table\Table;
use Conquest\Table\Tests\Pagination\Concerns\Classes\MethodTable;
use Conquest\Table\Tests\Pagination\Concerns\Classes\PropertyTable;
use Illuminate\Support\Facades\Request;

it('uses show key default as config', function () {
    expect(Table::make()->getShowKey())->toBe(config('table.pagination.key'));
});

it('can set a show key', function () {
    $table = Table::make();
    $table->setShowKey('display');
    expect($table->getShowKey())->toBe('display');
});

it('uses show key attribute', function () {
    expect(PropertyTable::make()->getShowKey())->toBe('count');
});

it('uses show key method', function () {
    expect(MethodTable::make()->getShowKey())->toBe('count');
});

it('prevents setting show key as null', function () {
    $table = Table::make();
    $table->setShowKey(null);
    expect($table->getShowKey())->toBe(config('table.pagination.key'));
});

it('gets show key from request using show key', function () {
    $table = Table::make();
    $table->setShowKey('display');
    Request::merge(['display' => '50']);
    expect($table->getPerPageFromRequest())->toBe(50);
});

it('returns null when show key is not in request', function () {
    $table = Table::make();
    $table->setShowKey('display');
    expect($table->getPerPageFromRequest())->toBe(0);
});

it('casts non-numeric values to integer', function () {
    $table = Table::make();
    $table->setShowKey('display');
    Request::merge(['display' => 'abc']);
    expect($table->getPerPageFromRequest())->toBe(0);
});

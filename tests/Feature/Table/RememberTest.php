<?php

use Conquest\Table\Table;
use Workbench\App\Models\Product;
use Conquest\Table\Columns\Column;

beforeEach(function () {
    Table::setGlobalToggleKey('cols');
    Table::setGlobalRememberFor(30*24*60*60);
});

it('can set a global toggle key', function () {
    $table = Table::make(
        resource: Product::query(),
        columns: [
            Column::make('id')->key(),
        ],
    );
    Table::setGlobalToggleKey('columns');
    expect($table->getToggleKey())->toBe('columns');
});

it('can set a global remember duration', function () {
    $table = Table::make(
        resource: Product::query(),
        columns: [
            Column::make('id')->key(),
        ],
    );
    Table::setGlobalRememberFor(20);
    expect($table->getRememberFor())->toBe(20);
});

it('does not remember by default', function () {
    $table = Table::make(
        resource: Product::query(),
        columns: [
            Column::make('id')->key(),
        ],
    );
    expect($table->getRememberKey())->toBeNull();
    expect($table->lacksRememberKey())->toBeTrue();
});

it('can set a remember key', function () {
    $table = Table::make(
        resource: Product::query(),
        columns: [
            Column::make('id')->key(),
        ],
    );
    $table->setRememberKey('remember');
    expect($table->getRememberKey())->toBe('remember');
});

// it('')
<?php

use Conquest\Table\Columns\Column;

beforeEach(function () {
    $this->column = Column::make('name');
});

it('has no suffix by default', function () {
    expect($this->column->getSuffix())->toBeNull();
    expect($this->column->hasSuffix())->toBeFalse();
    expect($this->column->lacksSuffix())->toBeTrue();
});

it('can set a suffix', function () {
    $this->column->setSuffix('.');
    expect($this->column->getSuffix())->toBe('.');
    expect($this->column->hasSuffix())->toBeTrue();
    expect($this->column->lacksSuffix())->toBeFalse();
});

it('can set a resolvable suffix', function () {
    $this->column->setSuffix(fn () => '.');
    expect($this->column->getSuffix())->toBe('.');
    expect($this->column->hasSuffix())->toBeTrue();
    expect($this->column->lacksSuffix())->toBeFalse();
});

it('can set a suffix through chaining', function () {
    expect($this->column->suffix('.'))->toBeInstanceOf(Column::class)
        ->getSuffix()->toBe('.')
        ->hasSuffix()->toBeTrue()
        ->lacksSuffix()->toBeFalse();
});

it('prevents null behaviour from being set', function () {
    $this->column->setSuffix(null);
    expect($this->column)
        ->getSuffix()->toBeNull()
        ->hasSuffix()->toBeFalse()
        ->lacksSuffix()->toBeTrue();
});

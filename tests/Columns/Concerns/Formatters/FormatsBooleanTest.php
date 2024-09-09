<?php

use Conquest\Table\Columns\Column;

beforeEach(function () {
    $this->column = Column::make('name');
});

it('does not format boolean by default', function () {
    expect($this->column->formatsBoolean())->toBeFalse();
});

it('can set as boolean', function () {
    expect($this->column->boolean())->toBeInstanceOf(Column::class)
        ->formatsBoolean()->toBeTrue()
        ->getTruthLabel()->toBe('Yes')
        ->getFalseLabel()->toBe('No');
});

it('can set truth label', function () {
    expect($this->column->boolean(true: 'Active'))
        ->formatsBoolean()->toBeTrue()
        ->getTruthLabel()->toBe('Active')
        ->getFalseLabel()->toBe('No');
});

it('can set a resolvable truth label', function () {
    expect($this->column->boolean(true: fn () => 'Active'))
        ->formatsBoolean()->toBeTrue()
        ->getTruthLabel()->toBe('Active')
        ->getFalseLabel()->toBe('No');
});

it('can set false label', function () {
    expect($this->column->boolean(false: 'Inactive'))
        ->formatsBoolean()->toBeTrue()
        ->getTruthLabel()->toBe('Yes')
        ->getFalseLabel()->toBe('Inactive');
});

it('can set a resolvable false label', function () {
    expect($this->column->boolean(false: fn () => 'Inactive'))
        ->formatsBoolean()->toBeTrue()
        ->getTruthLabel()->toBe('Yes')
        ->getFalseLabel()->toBe('Inactive');
});

it('can format boolean providing the true label', function () {
    expect($this->column->boolean())
        ->formatBoolean(1)->toBe('Yes')
        ->formatBoolean(true)->toBe('Yes')
        ->formatBoolean('a')->toBe('Yes')
        ->formatBoolean(fn () => 100)->toBe('Yes');
});

it('can format boolean providing the false label', function () {
    expect($this->column->boolean())
        ->formatBoolean(0)->toBe('No')
        ->formatBoolean(false)->toBe('No')
        ->formatBoolean('')->toBe('No')
        ->formatBoolean(fn () => false)->toBe('No');
});

it('allows for chaining of false label', function () {
    expect($this->column->boolean()->falseLabel('Inactive'))
        ->formatsBoolean()->toBeTrue()
        ->getTruthLabel()->toBe('Yes')
        ->getFalseLabel()->toBe('Inactive');
});

it('allows for chaining of true label', function () {
    expect($this->column->boolean()->truthLabel('Active'))
        ->formatsBoolean()->toBeTrue()
        ->getTruthLabel()->toBe('Active')
        ->getFalseLabel()->toBe('No');
});

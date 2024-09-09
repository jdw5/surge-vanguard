<?php

use Conquest\Table\Columns\DateColumn;

beforeEach(function () {
    $this->column = DateColumn::make('name');
});

it('has no format by default', function () {
    expect($this->column->getFormat())->toBeNull();
    expect($this->column->hasFormat())->toBeFalse();
    expect($this->column->lacksFormat())->toBeTrue();
});

it('can set a format', function () {
    $this->column->setFormat('d M Y');
    expect($this->column->getFormat())->toBe('d M Y');
    expect($this->column->hasFormat())->toBeTrue();
    expect($this->column->lacksFormat())->toBeFalse();
});

it('can set a resolvable format', function () {
    $this->column->setFormat(fn () => 'd M Y');
    expect($this->column->getFormat())->toBe('d M Y');
    expect($this->column->hasFormat())->toBeTrue();
    expect($this->column->lacksFormat())->toBeFalse();
});

it('can set a format through chaining', function () {
    expect($this->column->format('d M Y'))->toBeInstanceOf(DateColumn::class)
        ->getFormat()->toBe('d M Y')
        ->hasFormat()->toBeTrue()
        ->lacksFormat()->toBeFalse();
});

it('prevents null behaviour from being set', function () {
    $this->column->setFormat(null);
    expect($this->column)
        ->getFormat()->toBeNull()
        ->hasFormat()->toBeFalse()
        ->lacksFormat()->toBeTrue();
});

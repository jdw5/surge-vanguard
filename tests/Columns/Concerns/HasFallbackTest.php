<?php

use Conquest\Table\Columns\Column;

beforeEach(function () {
    $this->column = Column::make('name');
});

it('has no fallback by default', function () {
    expect($this->column->getFallback())->toBeNull();
    expect($this->column->hasFallback())->toBeFalse();
    expect($this->column->lacksFallback())->toBeTrue();
});

it('can set a fallback', function () {
    $this->column->setFallback('-');
    expect($this->column->getFallback())->toBe('-');
    expect($this->column->hasFallback())->toBeTrue();
    expect($this->column->lacksFallback())->toBeFalse();
});

it('can set a fallback through chaining', function () {
    expect($this->column->fallback('-'))->toBeInstanceOf(Column::class)
        ->getFallback()->toBe('-')
        ->hasFallback()->toBeTrue()
        ->lacksFallback()->toBeFalse();
});

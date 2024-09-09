<?php

use Conquest\Table\Columns\Column;

beforeEach(function () {
    $this->column = Column::make('name');
});

it('is not srOnly by default', function () {
    expect($this->column->isSrOnly())->toBeFalse();
    expect($this->column->isNotSrOnly())->toBeTrue();
});

it('can set as srOnly', function () {
    $this->column->setSrOnly(true);
    expect($this->column->isSrOnly())->toBeTrue();
    expect($this->column->isNotSrOnly())->toBeFalse();
});

it('can set as srOnly through resolver', function () {
    $this->column->setSrOnly(fn () => true);
    expect($this->column->isSrOnly())->toBeTrue();
    expect($this->column->isNotSrOnly())->toBeFalse();
});

it('can set as srOnly through chaining', function () {
    expect($this->column->srOnly())->toBeInstanceOf(Column::class)
        ->isSrOnly()->toBeTrue()
        ->isNotSrOnly()->toBeFalse();
});

it('prevents null behaviour from being set', function () {
    $this->column->setSrOnly(null);
    expect($this->column)
        ->isSrOnly()->toBeFalse()
        ->isNotSrOnly()->toBeTrue();
});

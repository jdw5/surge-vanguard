<?php

use Conquest\Table\Columns\Column;

beforeEach(function () {
    $this->column = Column::make('name');
});

it('is not toggleable by default', function () {
    expect($this->column->isToggleable())->toBeFalse();
    expect($this->column->isNotToggleable())->toBeTrue();
});

it('can set as toggleable', function () {
    $this->column->setToggleable(true);
    expect($this->column->isToggleable())->toBeTrue();
    expect($this->column->isNotToggleable())->toBeFalse();
});

it('can set as toggleable through resolver', function () {
    $this->column->setToggleable(fn () => true);
    expect($this->column->isToggleable())->toBeTrue();
    expect($this->column->isNotToggleable())->toBeFalse();
});

it('can set as toggleable through chaining', function () {
    expect($this->column->toggleable())->toBeInstanceOf(Column::class)
        ->isToggleable()->toBeTrue()
        ->isNotToggleable()->toBeFalse();
});

it('prevents null behaviour from being set', function () {
    $this->column->setToggleable(null);
    expect($this->column)
        ->isToggleable()->toBeFalse()
        ->isNotToggleable()->toBeTrue();
});
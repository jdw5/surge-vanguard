<?php

use Conquest\Table\Columns\Column;

beforeEach(function () {
    $this->column = Column::make('name');
});

it('has no tooltip by default', function () {
    expect($this->column->getTooltip())->toBeNull();
    expect($this->column->hasTooltip())->toBeFalse();
    expect($this->column->lacksTooltip())->toBeTrue();
});

it('can set a tooltip', function () {
    $this->column->setTooltip('Name');
    expect($this->column->getTooltip())->toBe('Name');
    expect($this->column->hasTooltip())->toBeTrue();
    expect($this->column->lacksTooltip())->toBeFalse();
});

it('can set a resolvable tooltip', function () {
    $this->column->setTooltip(fn () => 'Name');
    expect($this->column->getTooltip())->toBe('Name');
    expect($this->column->hasTooltip())->toBeTrue();
    expect($this->column->lacksTooltip())->toBeFalse();
});

it('can set a tooltip through chaining', function () {
    expect($this->column->tooltip('Name'))->toBeInstanceOf(Column::class)
        ->getTooltip()->toBe('Name')
        ->hasTooltip()->toBeTrue()
        ->lacksTooltip()->toBeFalse();
});

it('prevents null behaviour from being set', function () {
    $this->column->setTooltip(null);
    expect($this->column)
        ->getTooltip()->toBeNull()
        ->hasTooltip()->toBeFalse()
        ->lacksTooltip()->toBeTrue();
});

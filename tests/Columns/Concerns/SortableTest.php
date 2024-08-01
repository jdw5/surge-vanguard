<?php

/**
 * @todo: Add property tests then duplicate to searchable
 */
use Conquest\Table\Columns\Column;

beforeEach(function () {
    $this->column = Column::make('name');
});

it('is not sortable by default', function () {
    expect($this->column->isSortable())->toBeFalse();
    expect($this->column->isNotSortable())->toBeTrue();
    expect($this->column->hasSortProperty())->toBeFalse();
    expect($this->column->lacksSortProperty())->toBeTrue();
});

it('can set as sortable', function () {
    $this->column->setSortable(true);
    expect($this->column->isSortable())->toBeTrue();
    expect($this->column->isNotSortable())->toBeFalse();
    expect($this->column->hasSortProperty())->toBeFalse();
    expect($this->column->lacksSortProperty())->toBeTrue();
});

it('can set as sortable through resolver', function () {
    $this->column->setSortable(fn () => true);
    expect($this->column->isSortable())->toBeTrue();
    expect($this->column->isNotSortable())->toBeFalse();
    expect($this->column->hasSortProperty())->toBeFalse();
    expect($this->column->lacksSortProperty())->toBeTrue();
});

it('can set as sortable through chaining', function () {
    expect($this->column->sortable())->toBeInstanceOf(Column::class)
        ->isSortable()->toBeTrue()
        ->isNotSortable()->toBeFalse()
        ->hasSortProperty()->toBeFalse()
        ->lacksSortProperty()->toBeTrue();
});

it('can set a sort property', function () {
    $this->column->setSortProperty('uuid');
    expect($this->column)
        ->isSortable()->toBeFalse()
        ->isNotSortable()->toBeTrue()
        ->hasSortProperty()->toBeTrue()
        ->lacksSortProperty()->toBeFalse()
        ->getSortProperty()->toBe('uuid');
});

it('can set a sort property when enabling', function () {
    expect($this->column->sortable('uuid'))
        ->isSortable()->toBeTrue()
        ->isNotSortable()->toBeFalse()
        ->hasSortProperty()->toBeTrue()
        ->lacksSortProperty()->toBeFalse()
        ->getSortProperty()->toBe('uuid');
});

it('can set a resolvable sort property when enabling', function () {
    expect($this->column->sortable(fn () => 'uuid'))
        ->isSortable()->toBeTrue()
        ->isNotSortable()->toBeFalse()
        ->hasSortProperty()->toBeTrue()
        ->lacksSortProperty()->toBeFalse()
        ->getSortProperty()->toBe('uuid');
});

it('prevents null behaviour from being set for sortable', function () {
    $this->column->setSortable(null);
    expect($this->column)
        ->isSortable()->toBeFalse()
        ->isNotSortable()->toBeTrue();
});

it('prevents null behaviour from being set for sort property', function () {
    $this->column->setSortProperty(null);
    expect($this->column)
        ->hasSortProperty()->toBeFalse()
        ->lacksSortProperty()->toBeTrue();
});
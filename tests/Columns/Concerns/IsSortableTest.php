<?php

/**
 * @todo: Add property tests then duplicate to searchable
 */
use Conquest\Table\Columns\Column;
use Conquest\Table\Sorts\ToggleSort;

beforeEach(function () {
    $this->column = Column::make('name');
});

it('is not sortable by default', function () {
    expect($this->column->isSortable())->toBeFalse();
    expect($this->column->isNotSortable())->toBeTrue();
    expect($this->column->getSort())->toBeNull();
});

it('can set as sortable', function () {
    $this->column->setSortable();
    expect($this->column->isSortable())->toBeTrue();
    expect($this->column->isNotSortable())->toBeFalse();
    expect($this->column->getSort())->toBeInstanceOf(ToggleSort::class)
        ->getProperty()->toBe('name')
        ->getName()->toBe('name');

});

it('can set as sortable through chaining', function () {
    expect($this->column->sortable())->toBeInstanceOf(Column::class)
        ->isSortable()->toBeTrue()
        ->isNotSortable()->toBeFalse();

    expect($this->column->getSort())->toBeInstanceOf(ToggleSort::class)
        ->getProperty()->toBe('name')
        ->getName()->toBe('name');
});

it('can set a sort property', function () {
    $this->column->setSortable('uuid');
    expect($this->column->sortable('description')->getSort())
        ->getProperty()->toBe('description');
});

it('has a chain alias', function () {
    expect($this->column->sort())->toBeInstanceOf(Column::class)
        ->isSortable()->toBeTrue()
        ->isNotSortable()->toBeFalse();

    expect($this->column->getSort())->toBeInstanceOf(ToggleSort::class)
        ->getProperty()->toBe('name')
        ->getName()->toBe('name');
});

it('can check if sorting', function () {
    $this->column->setSortable();
    expect($this->column->isSorting())->toBeFalse();

    $this->column->getSort()->setActive(true);
    expect($this->column->isSorting())->toBeTrue();
});

<?php

/**
 * @todo: Add property tests then duplicate to searchable
 */
use Conquest\Table\Columns\Column;

beforeEach(function () {
    $this->column = Column::make('name');
});

it('is not searchable by default', function () {
    expect($this->column->isSearchable())->toBeFalse();
    expect($this->column->isNotSearchable())->toBeTrue();
    expect($this->column->hasSearchProperty())->toBeFalse();
    expect($this->column->lacksSearchProperty())->toBeTrue();
});

it('can set as searchable', function () {
    $this->column->setSearchable(true);
    expect($this->column->isSearchable())->toBeTrue();
    expect($this->column->isNotSearchable())->toBeFalse();
    expect($this->column->hasSearchProperty())->toBeFalse();
    expect($this->column->lacksSearchProperty())->toBeTrue();
});

it('can set as searchable through resolver', function () {
    $this->column->setSearchable(fn () => true);
    expect($this->column->isSearchable())->toBeTrue();
    expect($this->column->isNotSearchable())->toBeFalse();
    expect($this->column->hasSearchProperty())->toBeFalse();
    expect($this->column->lacksSearchProperty())->toBeTrue();
});

it('can set as searchable through chaining', function () {
    expect($this->column->searchable())->toBeInstanceOf(Column::class)
        ->isSearchable()->toBeTrue()
        ->isNotSearchable()->toBeFalse()
        ->hasSearchProperty()->toBeFalse()
        ->lacksSearchProperty()->toBeTrue();
});

it('can set a search property', function () {
    $this->column->setSearchProperty('uuid');
    expect($this->column)
        ->isSearchable()->toBeFalse()
        ->isNotSearchable()->toBeTrue()
        ->hasSearchProperty()->toBeTrue()
        ->lacksSearchProperty()->toBeFalse()
        ->getSearchProperty()->toBe('uuid');
});

it('can set a search property when enabling', function () {
    expect($this->column->searchable('uuid'))
        ->isSearchable()->toBeTrue()
        ->isNotSearchable()->toBeFalse()
        ->hasSearchProperty()->toBeTrue()
        ->lacksSearchProperty()->toBeFalse()
        ->getSearchProperty()->toBe('uuid');
});

it('can set a resolvable search property when enabling', function () {
    expect($this->column->searchable(fn () => 'uuid'))
        ->isSearchable()->toBeTrue()
        ->isNotSearchable()->toBeFalse()
        ->hasSearchProperty()->toBeTrue()
        ->lacksSearchProperty()->toBeFalse()
        ->getSearchProperty()->toBe('uuid');
});

it('prevents null behaviour from being set for searchable', function () {
    $this->column->setSearchable(null);
    expect($this->column)
        ->isSearchable()->toBeFalse()
        ->isNotSearchable()->toBeTrue();
});

it('prevents null behaviour from being set for search property', function () {
    $this->column->setSearchProperty(null);
    expect($this->column)
        ->hasSearchProperty()->toBeFalse()
        ->lacksSearchProperty()->toBeTrue();
});

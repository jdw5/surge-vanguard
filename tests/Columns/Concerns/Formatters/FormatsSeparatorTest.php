<?php

use Conquest\Table\Columns\Column;

beforeEach(function () {
    $this->column = Column::make('name');
});

it('does not format separator by default', function () {
    expect($this->column->formatsSeparator())->toBeFalse();
    expect($this->column->getSeparator())->toBeNull();
});

it('can set as separator', function () {
    expect($this->column->separator(' '))->toBeInstanceOf(Column::class)
        ->getSeparator()->toBe(' ')
        ->formatsSeparator()->toBeTrue();
});

it('can set a resolvable separator', function () {
    expect($this->column->separator(fn () => ' '))->toBeInstanceOf(Column::class)
        ->getSeparator()->toBe(' ')
        ->formatsSeparator()->toBeTrue();
});

it('can format a value using the separator', function () {
    expect($this->column->separator(' '))->toBeInstanceOf(Column::class)
        ->formatSeparator(['Mr', 'John'])->toBe('Mr John')
        ->formatSeparator('No array')->toBe('No array');

});

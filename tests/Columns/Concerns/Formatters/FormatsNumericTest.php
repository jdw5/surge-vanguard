<?php

use Conquest\Table\Columns\Column;

beforeEach(function () {
    $this->column = Column::make('name');
});

it('does not format numeric by default', function () {
    expect($this->column->formatsNumeric())->toBeFalse();
});

it('can set as numeric', function () {
    expect($this->column->numeric())->toBeInstanceOf(Column::class)
        ->formatsNumeric()->toBeTrue()
        ->hasDecimalPlaces()->toBeFalse()
        ->hasRoundToNearest()->toBeFalse()
        ->hasDivideBy()->toBeFalse()
        ->hasLocale()->toBeFalse();
});

it('can set decimal places', function () {
    expect($this->column->numeric(2))
        ->hasDecimalPlaces()->toBeTrue()
        ->lacksDecimalPlaces()->toBeFalse()
        ->getDecimalPlaces()->toBe(2);
});

it('can set a resolvable decimal places', function () {
    expect($this->column->numeric(fn () => 2))
        ->hasDecimalPlaces()->toBeTrue()
        ->lacksDecimalPlaces()->toBeFalse()
        ->getDecimalPlaces()->toBe(2);
});

it('can set rounded to nearest', function () {
    expect($this->column->numeric(roundToNearest: 10))
        ->hasRoundToNearest()->toBeTrue()
        ->lacksRoundToNearest()->toBeFalse()
        ->getRoundToNearest()->toBe(10);
});

it('can set a resolvable rounded to nearest', function () {
    expect($this->column->numeric(roundToNearest: fn () => 10))
        ->hasRoundToNearest()->toBeTrue()
        ->lacksRoundToNearest()->toBeFalse()
        ->getRoundToNearest()->toBe(10);
});

it('can set divide by', function () {
    expect($this->column->numeric(divideBy: 100))
        ->hasDivideBy()->toBeTrue()
        ->lacksDivideBy()->toBeFalse()
        ->getDivideBy()->toBe(100);
});

it('can set a resolvable divide by', function () {
    expect($this->column->numeric(divideBy: fn () => 100))
        ->hasDivideBy()->toBeTrue()
        ->lacksDivideBy()->toBeFalse()
        ->getDivideBy()->toBe(100);
});

it('can set locale', function () {
    expect($this->column->numeric(locale: 'au'))
        ->hasLocale()->toBeTrue()
        ->lacksLocale()->toBeFalse()
        ->getLocale()->toBe('au');
});

it('can set a resolvable locale', function () {
    expect($this->column->numeric(locale: fn () => 'au'))
        ->hasLocale()->toBeTrue()
        ->lacksLocale()->toBeFalse()
        ->getLocale()->toBe('au');
});


// it('can format a value using the numeric', function () {
//     expect($this->column->numeric(' '))->toBeInstanceOf(Column::class)
//         ->formatNumeric(['Mr', 'John'])->toBe('Mr John')
//         ->formatNumeric('No array')->toBe('No array');

// });
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

it('does not format non-numeric values', function () {
    expect($this->column->numeric(2)->formatNumeric('Mr John'))->toBe('Mr John');
});

it('formats to string', function () {
    expect($this->column->numeric()->formatNumeric(2000))->toBe('2000');
});

it('can format numeric using decimal places', function () {
    expect($this->column->numeric(decimalPlaces: 2))
        ->formatNumeric(1000)->toBe('1,000.00')
        ->formatNumeric(1000.123)->toBe('1,000.12')
        ->formatNumeric(1000.123456)->toBe('1,000.12');
});

it('can format numeric using divide by', function () {
    expect($this->column->numeric(divideBy: 100))
        ->formatNumeric(1000)->toBe('10')
        ->formatNumeric(1000.123)->toBe('10.00123')
        ->formatNumeric(10)->toBe('0.1');
});

it('can format numeric using round to nearest', function () {
    expect($this->column->numeric(roundToNearest: 100))
        ->formatNumeric(50)->toBe('100')
        ->formatNumeric(49)->toBe('0')
        ->formatNumeric(100)->toBe('100');
});

it('can format numeric using the locale', function () {
    expect($this->column->numeric(locale: 'de'))
        ->formatNumeric(100)->toBe('100')
        ->formatNumeric(100.123)->toBe('100,123')
        ->formatNumeric(1000.123)->toBe('1.000,123');
});

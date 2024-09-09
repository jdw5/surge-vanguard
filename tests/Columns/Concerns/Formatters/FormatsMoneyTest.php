<?php

use Conquest\Table\Columns\Column;

beforeEach(function () {
    $this->column = Column::make('name');
});

it('does not format money by default', function () {
    expect($this->column->formatsMoney())->toBeFalse();
});

it('can set as money', function () {
    expect($this->column->money())->toBeInstanceOf(Column::class)
        ->formatsMoney()->toBeTrue()
        ->hasCurrency()->toBeFalse()
        ->hasDivideBy()->toBeFalse()
        ->hasLocale()->toBeFalse();
});

it('can set currency', function () {
    expect($this->column->money('eur'))
        ->hasCurrency()->toBeTrue()
        ->lacksCurrency()->toBeFalse()
        ->getCurrency()->toBe('eur');
});

it('can set a resolvable currency', function () {
    expect($this->column->money(fn () => 'eur'))
        ->hasCurrency()->toBeTrue()
        ->lacksCurrency()->toBeFalse()
        ->getCurrency()->toBe('eur');
});

it('can set divide by', function () {
    expect($this->column->money(divideBy: 100))
        ->hasDivideBy()->toBeTrue()
        ->lacksDivideBy()->toBeFalse()
        ->getDivideBy()->toBe(100);
});

it('can set a resolvable divide by', function () {
    expect($this->column->money(divideBy: fn () => 100))
        ->hasDivideBy()->toBeTrue()
        ->lacksDivideBy()->toBeFalse()
        ->getDivideBy()->toBe(100);
});

it('can set locale', function () {
    expect($this->column->money(locale: 'au'))
        ->hasLocale()->toBeTrue()
        ->lacksLocale()->toBeFalse()
        ->getLocale()->toBe('au');
});

it('can set a resolvable locale', function () {
    expect($this->column->money(locale: fn () => 'au'))
        ->hasLocale()->toBeTrue()
        ->lacksLocale()->toBeFalse()
        ->getLocale()->toBe('au');
});

it('does not format non-money values', function () {
    expect($this->column->money()->formatMoney('Mr John'))->toBe('Mr John');
});

it('formats using USD by default', function () {
    expect($this->column->money()->formatMoney(2000))->toBe('$2,000.00');
});

it('can format money using currency', function () {
    expect($this->column->money(currency: 'eur'))
        ->formatMoney(1000)->toBe('€1,000.00')
        ->formatMoney(1000.123)->toBe('€1,000.12')
        ->formatMoney(1000.123456)->toBe('€1,000.12');
});

it('can format money using divide by', function () {
    expect($this->column->money(divideBy: 100))
        ->formatMoney(1000)->toBe('$10.00')
        ->formatMoney(1000.123)->toBe('$10.00')
        ->formatMoney(10)->toBe('$0.10');
});

it('can format money using the locale', function () {
    expect($this->column->money(locale: 'es'))
        ->formatMoney(100)->toBe('100,00 US$')

        ->formatMoney(100.125)->toBe('100,12 US$')
        ->formatMoney(1000.125)->toBe('1.000,12 US$');
});

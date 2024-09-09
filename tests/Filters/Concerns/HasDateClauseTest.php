<?php

use Conquest\Table\Filters\DateFilter;
use Conquest\Table\Filters\Enums\DateClause;

beforeEach(function () {
    $this->filter = DateFilter::make('name');
});

it('has is date clause by default', function () {
    expect($this->filter->getDateClause())->toBe(DateClause::Date);
    expect($this->filter->hasDateClause())->toBeTrue();
    expect($this->filter->lacksDateClause())->toBeFalse();
});

it('can set a date clause', function () {
    $this->filter->setDateClause(DateClause::Day);
    expect($this->filter->getDateClause())->toBe(DateClause::Day);
    expect($this->filter->hasDateClause())->toBeTrue();
    expect($this->filter->lacksDateClause())->toBeFalse();
});

it('can set a date clause by string', function () {
    $this->filter->setDateClause('day');
    expect($this->filter->getDateClause())->toBe(DateClause::Day);
    expect($this->filter->hasDateClause())->toBeTrue();
    expect($this->filter->lacksDateClause())->toBeFalse();
});

it('throws error if invalid string clause', function () {
    $this->filter->setDateClause('century');
})->throws(ValueError::class);

it('can set a date clause through chaining', function () {
    expect($this->filter->dateClause(DateClause::Day))->toBeInstanceOf(DateFilter::class)
        ->getDateClause()->toBe(DateClause::Day)
        ->hasDateClause()->toBeTrue()
        ->lacksDateClause()->toBeFalse();
});

it('prevents null behaviour from being set', function () {
    $this->filter->setDateClause(null);
    expect($this->filter)
        ->getDateClause()->toBe(DateClause::Date)
        ->hasDateClause()->toBeTrue()
        ->lacksDateClause()->toBeFalse();
});

it('can set "date" date clause through shorthand chain', function () {
    expect($this->filter->date())->toBeInstanceOf(DateFilter::class)
        ->getDateClause()->toBe(DateClause::Date);
});

it('can set "day" date clause through shorthand chain', function () {
    expect($this->filter->day())->toBeInstanceOf(DateFilter::class)
        ->getDateClause()->toBe(DateClause::Day);
});

it('can set "month" date clause through shorthand chain', function () {
    expect($this->filter->month())->toBeInstanceOf(DateFilter::class)
        ->getDateClause()->toBe(DateClause::Month);
});

it('can set "year" date clause through shorthand chain', function () {
    expect($this->filter->year())->toBeInstanceOf(DateFilter::class)
        ->getDateClause()->toBe(DateClause::Year);
});

it('can set "time" date clause through shorthand chain', function () {
    expect($this->filter->time())->toBeInstanceOf(DateFilter::class)
        ->getDateClause()->toBe(DateClause::Time);
});

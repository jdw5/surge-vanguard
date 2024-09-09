<?php

use Carbon\Carbon;
use Conquest\Table\Columns\DateColumn;

beforeEach(function () {
    Carbon::setTestNow(Carbon::parse('1st January 2000'));
    $this->column = DateColumn::make('name');
});

it('does not format since by default', function () {
    expect($this->column->formatsSince())->toBeFalse();
});

it('can set as since', function () {
    expect($this->column->since())->toBeInstanceOf(DateColumn::class)
        ->formatsSince()->toBeTrue();
});

it('can format since', function () {
    $this->column->since();
    expect($this->column->formatSince('31-12-1999'))->toBe('Yesterday');
});

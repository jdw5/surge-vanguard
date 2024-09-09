<?php

use Conquest\Table\Columns\Column;

beforeEach(function () {
    $this->column = Column::make('name');
});

it('has no breakpoint by default', function () {
    expect($this->column->getBreakpoint())->toBeNull();
    expect($this->column->hasBreakpoint())->toBeFalse();
    expect($this->column->lacksBreakpoint())->toBeTrue();
});

it('can set a breakpoint', function () {
    $this->column->setBreakpoint('sm');
    expect($this->column->getBreakpoint())->toBe('sm');
    expect($this->column->hasBreakpoint())->toBeTrue();
    expect($this->column->lacksBreakpoint())->toBeFalse();
});

it('can set a breakpoint through chaining', function () {
    expect($this->column->breakpoint('sm'))->toBeInstanceOf(Column::class)
        ->getBreakpoint()->toBe('sm')
        ->hasBreakpoint()->toBeTrue()
        ->lacksBreakpoint()->toBeFalse();
});

it('can set a breakpoint as xs', function () {
    expect($this->column->xs())->toBeInstanceOf(Column::class)
        ->getBreakpoint()->toBe('xs')
        ->hasBreakpoint()->toBeTrue()
        ->lacksBreakpoint()->toBeFalse();
});

it('can set a breakpoint as sm', function () {
    expect($this->column->sm())->toBeInstanceOf(Column::class)
        ->getBreakpoint()->toBe('sm')
        ->hasBreakpoint()->toBeTrue()
        ->lacksBreakpoint()->toBeFalse();
});

it('can set a breakpoint as md', function () {
    expect($this->column->md())->toBeInstanceOf(Column::class)
        ->getBreakpoint()->toBe('md')
        ->hasBreakpoint()->toBeTrue()
        ->lacksBreakpoint()->toBeFalse();
});

it('can set a breakpoint as lg', function () {
    expect($this->column->lg())->toBeInstanceOf(Column::class)
        ->getBreakpoint()->toBe('lg')
        ->hasBreakpoint()->toBeTrue()
        ->lacksBreakpoint()->toBeFalse();
});

it('can set a breakpoint as xl', function () {
    expect($this->column->xl())->toBeInstanceOf(Column::class)
        ->getBreakpoint()->toBe('xl')
        ->hasBreakpoint()->toBeTrue()
        ->lacksBreakpoint()->toBeFalse();
});

it('is case insensitive', function () {
    expect($this->column->breakpoint('SM'))->toBeInstanceOf(Column::class)
        ->getBreakpoint()->toBe('sm')
        ->hasBreakpoint()->toBeTrue()
        ->lacksBreakpoint()->toBeFalse();
});

it('prevents null behaviour from being set', function () {
    $this->column->setBreakpoint(null);
    expect($this->column)
        ->getBreakpoint()->toBeNull()
        ->hasBreakpoint()->toBeFalse()
        ->lacksBreakpoint()->toBeTrue();
});

it('validates the given breakpoint', function () {
    $this->column->breakpoint('xxs');
})->throws(InvalidArgumentException::class);

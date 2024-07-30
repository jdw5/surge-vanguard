<?php

use Conquest\Table\Columns\NumericColumn;
use Conquest\Table\Columns\Enums\Breakpoint;

it('can create a numeric column', function () {
    $col = new NumericColumn('name');
    expect($col)->toBeInstanceOf(NumericColumn::class)
        ->getType()->toBe('col:numeric')
        ->getName()->toBe('name')
        ->getLabel()->toBe('Name')
        ->isHidden()->toBeFalse()
        ->getFallback()->toBe(config('table.fallback.numeric'))
        ->isAuthorized()->toBeTrue()
        ->canTransform()->toBeFalse()
        ->getBreakpoint()->toBeNull()
        ->isSrOnly()->toBeFalse()
        ->hasSort()->toBeFalse()
        ->isActive()->toBeTrue()
        ->isKey()->toBeFalse()
        ->hasMeta()->toBeFalse();
});

it('can make a numeric column', function () {
    expect(NumericColumn::make('name'))->toBeInstanceOf(NumericColumn::class)
        ->getType()->toBe('col:numeric')
        ->getName()->toBe('name')
        ->getLabel()->toBe('Name')
        ->isHidden()->toBeFalse()
        ->getFallback()->toBe(0)
        ->isAuthorized()->toBeTrue()
        ->canTransform()->toBeFalse()
        ->getBreakpoint()->toBeNull()
        ->isSrOnly()->toBeFalse()
        ->hasSort()->toBeFalse()
        ->isActive()->toBeTrue()
        ->isKey()->toBeFalse()
        ->hasMeta()->toBeFalse();
});

it('can create a numeric column with arguments', function () {
    $col = new NumericColumn(
        name: 'name',
        label: $label = 'Username',
        hidden: true,
        fallback: 'N/A',
        authorize: fn () => false,
        transform: fn ($value) => $value,
        breakpoint: Breakpoint::XS,
        srOnly: true,
        sortable: true,
        active: false,
        key: true,
        meta: ['key' => 'value'],
    );

    expect($col)->toBeInstanceOf(NumericColumn::class)
        ->getType()->toBe('col:numeric')
        ->getName()->toBe('name')
        ->getLabel()->toBe($label)
        ->isHidden()->toBeTrue()
        ->getFallback()->toBe('N/A')
        ->isAuthorized()->toBeFalse()
        ->canTransform()->toBeTrue()
        ->getBreakpoint()->toBe('xs')
        ->isSrOnly()->toBeTrue()
        ->hasSort()->toBeTrue()
        ->isActive()->toBeFalse()
        ->isKey()->toBeTrue()
        ->hasMeta()->toBeTrue();
});



it('can chain methods on a numeric column', function () {
    $col = NumericColumn::make('name')
        ->label($label = 'Username')
        ->hidden()
        ->fallback('N/A')
        ->authorize(fn () => false)
        ->transform(fn ($value) => strtoupper($value))
        ->breakpoint('xs')
        ->srOnly()
        ->sortable()
        ->active(false)
        ->key()
        ->meta(['key' => 'value']);
        
    expect($col)->toBeInstanceOf(NumericColumn::class)
        ->getType()->toBe('col:numeric')
        ->getName()->toBe('name')
        ->getLabel()->toBe($label)
        ->isHidden()->toBeTrue()
        ->getFallback()->toBe('N/A')
        ->isAuthorized()->toBeFalse()
        ->canTransform()->toBeTrue()
        ->getBreakpoint()->toBe('xs')
        ->isSrOnly()->toBeTrue()
        ->hasSort()->toBeTrue()
        ->isActive()->toBeFalse()
        ->isKey()->toBeTrue()
        ->hasMeta()->toBeTrue();
});

it('does not allow the name to be "actions"', function () {
    expect(fn () => new NumericColumn('actions'))->toThrow(Exception::class, 'Column name cannot be "actions"');
});

it('can apply a column and fallbacks with value', function () {
    $fn = fn ($value) => strtoupper($value);
    $col = NumericColumn::make('name')->transform($fn);
    expect($col->apply('test'))->toBe('TEST');
    expect($col->apply(null))->toBe(config('table.fallback.numeric'));
});

it('has array form', function () {
    $col = NumericColumn::make('name');
    expect($col->toArray())->toEqual([
        'name' => 'name',
        'label' => 'Name',
        'hidden' => false,
        'active' => true,
        'breakpoint' => null,
        'srOnly' => false,
        'sort' => false,
        'sorting' => false,
        'direction' => null,
        'meta' => [],
        'fallback' => config('table.fallback.numeric'),
    ]);
});

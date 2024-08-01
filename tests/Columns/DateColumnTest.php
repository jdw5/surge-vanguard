<?php

use Conquest\Table\Columns\DateColumn;

it('can create a date column', function () {
    $col = new DateColumn('name');
    expect($col)->toBeInstanceOf(DateColumn::class)
        ->getType()->toBe('col:date')
        ->getName()->toBe('name')
        ->getLabel()->toBe('Name')
        ->hasSort()->toBeFalse()
        ->isSearchable()->toBeFalse()
        ->getBreakpoint()->toBeNull()
        ->isAuthorized()->toBeTrue()
        ->getFallback()->toBeNull()
        ->isHidden()->toBeFalse()
        ->isSrOnly()->toBeFalse()
        ->canTransform()->toBeFalse()
        ->isActive()->toBeTrue()
        ->isKey()->toBeFalse()
        ->hasFormat()->toBeFalse()
        ->hasMeta()->toBeFalse();
});

it('can make a date column', function () {
    expect(DateColumn::make('name'))->toBeInstanceOf(DateColumn::class)
        ->getType()->toBe('col:date')
        ->getName()->toBe('name')
        ->getLabel()->toBe('Name')
        ->isHidden()->toBeFalse()
        ->getFallback()->toBeNull()
        ->isAuthorized()->toBeTrue()
        ->canTransform()->toBeFalse()
        ->getBreakpoint()->toBeNull()
        ->isSrOnly()->toBeFalse()
        ->hasSort()->toBeFalse()
        ->isSearchable()->toBeFalse()
        ->isActive()->toBeTrue()
        ->isKey()->toBeFalse()
        ->hasFormat()->toBeFalse()
        ->hasMeta()->toBeFalse();
});

it('can chain methods on a date column', function () {
    $col = DateColumn::make('name')
        ->label($label = 'Username')
        ->sortable()
        ->searchable()
        ->breakpoint('xs')
        ->authorize(fn () => false)
        ->fallback('N/A')
        ->hidden()
        ->srOnly()
        ->transform(fn ($value) => strtoupper($value))
        ->format('d M Y');
        
    expect($col)->toBeInstanceOf(DateColumn::class)
        ->getType()->toBe('col:date')
        ->getName()->toBe('name')
        ->getLabel()->toBe($label)
        ->isHidden()->toBeTrue()
        ->getFallback()->toBe('N/A')
        ->isAuthorized()->toBeFalse()
        ->canTransform()->toBeTrue()
        ->getBreakpoint()->toBe('xs')
        ->isSrOnly()->toBeTrue()
        ->hasSort()->toBeTrue()
        ->isSearchable()->toBeTrue()
        ->isActive()->toBeTrue()
        ->isKey()->toBeFalse()
        ->hasMeta()->toBeFalse()
        ->getFormat()->toBe('d M Y');
});

it('does not allow the name to be "actions"', function () {
    expect(fn () => new DateColumn('actions'))->toThrow(Exception::class, 'Column name cannot be "actions"');
});

it('can format a date', function () {
    $col = DateColumn::make('created_at')->format('d M Y');
    expect($col->apply('01-01-2001'))->toBe('01 Jan 2001');
    expect($col->apply(null))->toBeNull();
});

it('has array form', function () {
    $col = DateColumn::make('name');
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
        'fallback' => config('table.fallback.default'),
    ]);
});
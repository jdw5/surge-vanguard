<?php

use Conquest\Table\Columns\Column;

it('can create a column', function () {
    $col = new Column('name');
    expect($col)->toBeInstanceOf(Column::class)
        ->getType()->toBe('col')
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
        ->hasMeta()->toBeFalse()
        ->isKey()->toBeFalse();
});

it('can make a column', function () {
    expect(Column::make('name'))->toBeInstanceOf(Column::class)
        ->getType()->toBe('col')
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
        ->hasMeta()->toBeFalse();
});

it('can chain methods on a column', function () {
    $col = Column::make('name')
        ->label($label = 'Username')
        ->sortable()
        ->searchable()
        ->breakpoint('xs')
        ->authorize(fn () => false)
        ->fallback('N/A')
        ->hidden()
        ->srOnly()
        ->transform(fn ($value) => strtoupper($value));

    expect($col)->toBeInstanceOf(Column::class)
        ->getType()->toBe('col')
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
        ->hasMeta()->toBeFalse();
});

it('can apply a column and fallbacks with value', function () {
    $fn = fn ($value) => strtoupper($value);
    $col = Column::make('name')->fallback('Exists')->transform($fn);

    expect($col->apply('test'))->toBe('TEST');
    expect($col->apply(null))->toBe('Exists');
});

it('does not allow the name to be "actions"', function () {
    expect(fn () => new Column('actions'))->toThrow(Exception::class, 'Column name cannot be "actions"');
});

it('has array form', function () {
    $col = Column::make('name');
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

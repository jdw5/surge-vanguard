<?php

use Conquest\Table\Columns\Column;
use Conquest\Table\Columns\Enums\Breakpoint;

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
        ->hasMetadata()->toBeFalse()
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
        ->hasMetadata()->toBeFalse();
});

it('can create a column with arguments', function () {
    $col = new Column(
        name: 'name',
        label: $label = 'Username',
        hidden: true,
        fallback: 'N/A',
        authorize: fn () => false,
        transform: fn ($value) => mb_strtoupper($value),
        breakpoint: Breakpoint::XS,
        srOnly: true,
        sortable: true,
        searchable: true,
        active: false,
        key: true,
        metadata: ['key' => 'value'],
    );

    expect($col)->toBeInstanceOf(Column::class)
        ->getType()->toBe('col')
        ->getName()->toBe('name')
        ->getLabel()->toBe($label)
        ->hasSort()->toBeTrue()
        ->isSearchable()->toBeTrue()
        ->getBreakpoint()->toBe('xs')
        ->isAuthorized()->toBeFalse()
        ->getFallback()->toBe('N/A')
        ->isHidden()->toBeTrue()
        ->isSrOnly()->toBeTrue()
        ->canTransform()->toBeTrue()
        ->isActive()->toBeFalse()
        ->isKey()->toBeTrue()
        ->hasMetadata()->toBeTrue();
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
        ->hasMetadata()->toBeFalse();
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
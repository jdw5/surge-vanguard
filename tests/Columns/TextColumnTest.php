<?php

use Conquest\Table\Columns\TextColumn;
use Conquest\Table\Columns\Enums\Breakpoint;

it('can create a text column', function () {
    $col = new TextColumn('name');
    expect($col)->toBeInstanceOf(TextColumn::class)
        ->getType()->toBe('col:text')
        ->getName()->toBe('name')
        ->getLabel()->toBe('Name')
        ->isHidden()->toBeFalse()
        ->getFallback()->toBe(config('table.fallback.text'))
        ->isAuthorized()->toBeTrue()
        ->canTransform()->toBeFalse()
        ->getBreakpoint()->toBeNull()
        ->isSrOnly()->toBeFalse()
        ->hasSort()->toBeFalse()
        ->isActive()->toBeTrue()
        ->isKey()->toBeFalse()
        ->hasMeta()->toBeFalse();
});

it('can make a text column', function () {
    expect(TextColumn::make('name'))->toBeInstanceOf(TextColumn::class)
        ->getType()->toBe('col:text')
        ->getName()->toBe('name')
        ->getLabel()->toBe('Name')
        ->isHidden()->toBeFalse()
        ->getFallback()->toBe(config('table.fallback.text'))
        ->isAuthorized()->toBeTrue()
        ->canTransform()->toBeFalse()
        ->getBreakpoint()->toBeNull()
        ->isSrOnly()->toBeFalse()
        ->hasSort()->toBeFalse()
        ->isActive()->toBeTrue()
        ->isKey()->toBeFalse()
        ->hasMeta()->toBeFalse();
});

it('can chain methods on a text column', function () {
    $col = TextColumn::make('name')
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
        
    expect($col)->toBeInstanceOf(TextColumn::class)
        ->getType()->toBe('col:text')
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
    expect(fn () => new TextColumn('actions'))->toThrow(Exception::class, 'Column name cannot be "actions"');
});

it('can apply a column and fallbacks with value', function () {
    $fn = fn ($value) => strtoupper($value);
    $col = TextColumn::make('name')->transform($fn);
    expect($col->apply('test'))->toBe('TEST');
    expect($col->apply(null))->toBe(config('table.fallback.text'));
});

it('has array form', function () {
    $col = TextColumn::make('name');
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
        'fallback' => config('table.fallback.text'),
    ]);
});
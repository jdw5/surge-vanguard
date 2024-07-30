<?php

use Conquest\Table\Columns\BooleanColumn;
use Conquest\Table\Columns\Enums\Breakpoint;

it('can create a boolean column', function () {
    $col = new BooleanColumn('name');
    expect($col)->toBeInstanceOf(BooleanColumn::class)
        ->getType()->toBe('col:bool')
        ->getName()->toBe('name')
        ->getLabel()->toBe('Name')
        ->isHidden()->toBeFalse()
        ->isAuthorized()->toBeTrue()
        ->canTransform()->toBeFalse()
        ->getBreakpoint()->toBeNull()
        ->isSrOnly()->toBeFalse()
        ->hasSort()->toBeFalse()
        ->isActive()->toBeTrue()
        ->getTruthLabel()->toBe('Active')
        ->getFalseLabel()->toBe('Inactive')
        ->hasMeta()->toBeFalse();
});

it('can make a boolean column', function () {
    expect(BooleanColumn::make('name'))->toBeInstanceOf(BooleanColumn::class)
        ->getType()->toBe('col:bool')
        ->getName()->toBe('name')
        ->getLabel()->toBe('Name')
        ->isHidden()->toBeFalse()
        ->isAuthorized()->toBeTrue()
        ->canTransform()->toBeFalse()
        ->getBreakpoint()->toBeNull()
        ->isSrOnly()->toBeFalse()
        ->hasSort()->toBeFalse()
        ->isActive()->toBeTrue()
        ->getTruthLabel()->toBe('Active')
        ->getFalseLabel()->toBe('Inactive')
        ->hasMeta()->toBeFalse();
});

it('can create a boolean column with arguments', function () {
    $col = new BooleanColumn(
        name: 'name',
        label: $label = 'Username',
        hidden: true,
        authorize: fn () => false,
        transform: fn ($value) => $value,
        breakpoint: Breakpoint::XS,
        srOnly: true,
        sortable: true,
        active: false,
        truthLabel: 'Yes',
        falseLabel: 'No',
        meta: ['key' => 'value'],
    );

    expect($col)->toBeInstanceOf(BooleanColumn::class)
        ->getType()->toBe('col:bool')
        ->getName()->toBe('name')
        ->getLabel()->toBe($label)
        ->isHidden()->toBeTrue()
        ->isAuthorized()->toBeFalse()
        ->canTransform()->toBeTrue()
        ->getBreakpoint()->toBe('xs')
        ->isSrOnly()->toBeTrue()
        ->hasSort()->toBeTrue()
        ->isActive()->toBeFalse()
        ->getTruthLabel()->toBe('Yes')
        ->getFalseLabel()->toBe('No')
        ->hasMeta()->toBeTrue();
});



it('can chain methods on a boolean column', function () {
    $col = BooleanColumn::make('name')
        ->label($label = 'Username')
        ->hidden()
        ->authorize(fn () => false)
        ->transform(fn ($value) => strtoupper($value))
        ->breakpoint('xs')
        ->srOnly()
        ->sortable()
        ->active(false)
        ->falseLabel(fn () => 'No')
        ->truthLabel(fn () => 'Yes')
        ->meta(['key' => 'value']);
        
    expect($col)->toBeInstanceOf(BooleanColumn::class)
        ->getType()->toBe('col:bool')
        ->getName()->toBe('name')
        ->getLabel()->toBe($label)
        ->isHidden()->toBeTrue()
        ->isAuthorized()->toBeFalse()
        ->canTransform()->toBeTrue()
        ->getBreakpoint()->toBe('xs')
        ->isSrOnly()->toBeTrue()
        ->hasSort()->toBeTrue()
        ->isActive()->toBeFalse()
        ->getTruthLabel()->toBe('Yes')
        ->getFalseLabel()->toBe('No')
        ->hasMeta()->toBeTrue();
});

it('does not allow the name to be "actions"', function () {
    expect(fn () => new BooleanColumn('actions'))->toThrow(Exception::class, 'Column name cannot be "actions"');
});

it('can apply a boolean', function () {
    $col = BooleanColumn::make('created_at');
    expect($col->apply(null))->toBe('Inactive');
    expect($col->apply(1))->toBe('Active');
    $col->transform(fn ($value) => $value > 0);
    expect($col->apply(-1))->toBe('Inactive');
    expect($col->apply(1))->toBe('Active');
});

it('has array form', function () {
    $col = BooleanColumn::make('name');
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
    ]);
});
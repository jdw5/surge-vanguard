<?php

use Conquest\Table\Columns\BooleanColumn;
use Conquest\Table\Columns\Enums\Breakpoint;

it('can create a boolean column', function () {
    $col = new BooleanColumn('name');
    expect($col)->toBeInstanceOf(BooleanColumn::class)
        ->getType()->toBe('col:bool')
        ->getName()->toBe('name')
        ->getLabel()->toBe('Name')
        ->hasSort()->toBeFalse()
        ->isSearchable()->toBeFalse()
        ->getBreakpoint()->toBeNull()
        ->isAuthorized()->toBeTrue()
        ->isHidden()->toBeFalse()
        ->isSrOnly()->toBeFalse()
        ->canTransform()->toBeFalse()
        ->isToggledOn()->toBeTrue()
        ->isKey()->toBeFalse()
        ->getTruthLabel()->toBe('Active')
        ->getFalseLabel()->toBe('Inactive')
        ->hasMetadata()->toBeFalse();
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
        ->isToggledOn()->toBeTrue()
        ->getTruthLabel()->toBe('Active')
        ->getFalseLabel()->toBe('Inactive')
        ->hasMetadata()->toBeFalse();
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
        metadata: ['key' => 'value'],
    );

    expect($col)->toBeInstanceOf(BooleanColumn::class)
        ->getType()->toBe('col:bool')
        ->getName()->toBe('name')
        ->getLabel()->toBe($label)
        ->hasSort()->toBeTrue()
        ->getBreakpoint()->toBe('xs')
        ->isAuthorized()->toBeFalse()
        ->isHidden()->toBeTrue()
        ->isSrOnly()->toBeTrue()
        ->canTransform()->toBeTrue()
        ->isToggledOn()->toBeFalse()
        ->getTruthLabel()->toBe('Yes')
        ->getFalseLabel()->toBe('No')
        ->hasMetadata()->toBeTrue();
});



it('can chain methods on a boolean column', function () {
    $col = BooleanColumn::make('name')
        ->label($label = 'Username')
        ->sortable()
        ->breakpoint('xs')
        ->authorize(fn () => false)
        ->hidden()
        ->srOnly()
        ->transform(fn ($value) => strtoupper($value))
        ->off()
        ->falseLabel(fn () => 'No')
        ->truthLabel(fn () => 'Yes');
        
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
        ->isToggledOn()->toBeFalse()
        ->hasMetadata()->toBeFalse()
        ->getTruthLabel()->toBe('Yes')
        ->getFalseLabel()->toBe('No');
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

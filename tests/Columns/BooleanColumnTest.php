<?php

use Conquest\Table\Columns\BooleanColumn;
use Conquest\Table\Sorts\ToggleSort;

it('can instantiate a boolean column', function () {
    $col = new BooleanColumn('active');
    expect($col)->toBeInstanceOf(BooleanColumn::class)
        ->getName()->toBe('active')
        ->getLabel()->toBe('Active')
        ->getType()->toBe('boolean')
        ->isSortable()->toBeFalse()
        ->getSort()->toBeNull()
        ->isToggleable()->toBeFalse()
        ->isToggledOn()->toBeTrue()
        ->hasBreakpoint()->toBeFalse()
        ->isAuthorized()->toBeTrue()
        ->isHidden()->toBeFalse()
        ->isSrOnly()->toBeFalse()
        ->canTransform()->toBeFalse()
        ->hasMeta()->toBeFalse()
        ->hasPrefix()->toBeFalse()
        ->hasSuffix()->toBeFalse()
        ->hasTooltip()->toBeFalse();
});

describe('base make', function () {
    beforeEach(function () {
        $this->col = BooleanColumn::make('active');
    });

    it('can make a boolean column', function () {
        expect($this->col)->toBeInstanceOf(BooleanColumn::class)
            ->getName()->toBe('active')
            ->getLabel()->toBe('Active')
            ->getType()->toBe('boolean')
            ->isSortable()->toBeFalse()
            ->getSort()->toBeNull()
            ->isToggleable()->toBeFalse()
            ->isToggledOn()->toBeTrue()
            ->hasBreakpoint()->toBeFalse()
            ->isAuthorized()->toBeTrue()
            ->isHidden()->toBeFalse()
            ->isSrOnly()->toBeFalse()
            ->canTransform()->toBeFalse()
            ->hasMeta()->toBeFalse()
            ->hasPrefix()->toBeFalse()
            ->hasSuffix()->toBeFalse()
            ->hasTooltip()->toBeFalse();
    });

    it('has array form', function () {
        expect($this->col->toArray())->toEqual([
            'name' => 'active',
            'label' => 'Active',
            'type' => 'boolean',
            'hidden' => false,
            'placeholder' => null,
            'tooltip' => null,
            'breakpoint' => null,
            'sr' => false,
            'toggleable' => false,
            'active' => true,
            'sortable' => false,
            'sorting' => false,
            'direction' => null,
            'meta' => [],
            'prefix' => null,
            'suffix' => null,
        ]);
    });
});

describe('chain make', function () {
    beforeEach(function () {
        $this->col = BooleanColumn::make('active')
            ->label('When')
            ->sortable()
            ->toggleable(false)
            ->breakpoint('md')
            ->authorize(fn () => false)
            ->tooltip('Tooltip')
            ->placeholder('No boolean')
            ->hidden()
            ->srOnly()
            ->transform(fn ($value) => mb_strtoupper($value))
            ->meta(['key' => 'value'])
            ->suffix('.')
            ->prefix('@');
    });

    it('can chain methods on a boolean column', function () {
        expect($this->col)->toBeInstanceOf(BooleanColumn::class)
            ->getName()->toBe('active')
            ->getLabel()->toBe('When')
            ->getType()->toBe('boolean')
            ->isSortable()->toBeTrue()
            ->getSort()->toBeInstanceOf(ToggleSort::class)
            ->isToggleable()->toBeTrue()
            ->isToggledOn()->toBeFalse()
            ->hasBreakpoint()->toBeTrue()
            ->getBreakpoint()->toBe('md')
            ->isAuthorized()->toBeFalse()
            ->isHidden()->toBeTrue()
            ->isSrOnly()->toBeTrue()
            ->canTransform()->toBeTrue()
            ->hasMeta()->toBeTrue()
            ->getMeta()->toBe(['key' => 'value'])
            ->hasPrefix()->toBeTrue()
            ->getPrefix()->toBe('@')
            ->hasSuffix()->toBeTrue()
            ->getSuffix()->toBe('.')
            ->hasTooltip()->toBeTrue()
            ->getTooltip()->toBe('Tooltip');
    });

    it('has array form', function () {
        expect($this->col->toArray())->toEqual([
            'name' => 'active',
            'label' => 'When',
            'type' => 'boolean',
            'hidden' => true,
            'placeholder' => 'No boolean',
            'tooltip' => 'Tooltip',
            'breakpoint' => 'md',
            'sr' => true,
            'toggleable' => true,
            'active' => false,
            'sortable' => true,
            'sorting' => false,
            'direction' => null,
            'meta' => ['key' => 'value'],
            'prefix' => '@',
            'suffix' => '.',
        ]);
    });
});

it('does not allow boolean column name to be actions from instantiation', function () {
    $col = new BooleanColumn('actions');
})->throws(InvalidArgumentException::class, 'Column name cannot be "actions"');

it('does not allow boolean column name to be actions from make', function () {
    $col = BooleanColumn::make('actions');
})->throws(InvalidArgumentException::class, 'Column name cannot be "actions"');

it('can apply a boolean column for falsy values', function () {
    $col = BooleanColumn::make('active');
    expect($col->apply(null))->toBe('No');
});

it('can apply a boolean column for truthy values', function () {
    $col = BooleanColumn::make('active');
    expect($col->apply(1))->toBe('Yes');
});

it('can apply a boolean column and transforms value', function () {
    $col = BooleanColumn::make('active')->transform(fn ($value) => ! $value);
    expect($col->apply('Example'))->toBe('No');
});

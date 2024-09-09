<?php

use Conquest\Table\Columns\KeyColumn;
use Conquest\Table\Sorts\ToggleSort;

it('can instantiate a key column', function () {
    $col = new KeyColumn('id');
    expect($col)->toBeInstanceOf(KeyColumn::class)
        ->getName()->toBe('id')
        ->getLabel()->toBe('Id')
        ->getType()->toBe('key')
        ->isSortable()->toBeFalse()
        ->getSort()->toBeNull()
        ->isToggleable()->toBeFalse()
        ->isToggledOn()->toBeTrue()
        ->hasBreakpoint()->toBeFalse()
        ->isAuthorized()->toBeTrue()
        ->isSrOnly()->toBeFalse()
        ->canTransform()->toBeFalse()
        ->hasMeta()->toBeFalse()
        ->hasPrefix()->toBeFalse()
        ->hasSuffix()->toBeFalse()
        ->hasTooltip()->toBeFalse()
        ->isKey()->toBeTrue()
        ->isHidden()->toBeFalse();
});

describe('base make', function () {
    beforeEach(function () {
        $this->col = KeyColumn::make('id');
    });

    it('can make a key column', function () {
        expect($this->col)->toBeInstanceOf(KeyColumn::class)
            ->getName()->toBe('id')
            ->getLabel()->toBe('Id')
            ->getType()->toBe('key')
            ->isSortable()->toBeFalse()
            ->getSort()->toBeNull()
            ->isToggleable()->toBeFalse()
            ->isToggledOn()->toBeTrue()
            ->hasBreakpoint()->toBeFalse()
            ->isAuthorized()->toBeTrue()
            ->isSrOnly()->toBeFalse()
            ->canTransform()->toBeFalse()
            ->hasMeta()->toBeFalse()
            ->hasPrefix()->toBeFalse()
            ->hasSuffix()->toBeFalse()
            ->hasTooltip()->toBeFalse()
            ->isKey()->toBeTrue()
            ->isHidden()->toBeFalse();
    });

    it('has array form', function () {
        expect($this->col->toArray())->toEqual([
            'name' => 'id',
            'label' => 'Id',
            'type' => 'key',
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
        $this->col = KeyColumn::make('id')
            ->label('Identifier')
            ->sortable()
            ->toggleable(false)
            ->breakpoint('md')
            ->authorize(fn () => false)
            ->tooltip('Tooltip')
            ->placeholder('No key')
            ->hidden()
            ->srOnly()
            ->transform(fn ($value) => mb_strtoupper($value))
            ->meta(['key' => 'value'])
            ->suffix('.')
            ->prefix('#');
    });

    it('can chain methods on a key column', function () {
        expect($this->col)->toBeInstanceOf(KeyColumn::class)
            ->getName()->toBe('id')
            ->getLabel()->toBe('Identifier')
            ->getType()->toBe('key')
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
            ->getPrefix()->toBe('#')
            ->hasSuffix()->toBeTrue()
            ->getSuffix()->toBe('.')
            ->hasTooltip()->toBeTrue()
            ->getTooltip()->toBe('Tooltip')
            ->isKey()->toBeTrue();
    });

    it('has array form', function () {
        expect($this->col->toArray())->toEqual([
            'name' => 'id',
            'label' => 'Identifier',
            'type' => 'key',
            'hidden' => true,
            'placeholder' => 'No key',
            'tooltip' => 'Tooltip',
            'breakpoint' => 'md',
            'sr' => true,
            'toggleable' => true,
            'active' => false,
            'sortable' => true,
            'sorting' => false,
            'direction' => null,
            'meta' => ['key' => 'value'],
            'prefix' => '#',
            'suffix' => '.',
        ]);
    });
});

it('does not allow key column name to be actions from instantiation', function () {
    $col = new KeyColumn('actions');
})->throws(InvalidArgumentException::class, 'Column name cannot be "actions"');

it('does not allow key column name to be actions from make', function () {
    $col = KeyColumn::make('actions');
})->throws(InvalidArgumentException::class, 'Column name cannot be "actions"');

it('can apply a key column for same output', function () {
    $col = KeyColumn::make('id');
    expect($col->apply(null))->toBe(null);
});

it('can apply a key column and transforms value', function () {
    $col = KeyColumn::make('id')->transform(fn ($value) => mb_strtoupper($value));
    expect($col->apply('id'))->toBe('id');
});

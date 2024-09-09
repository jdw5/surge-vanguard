<?php

use Conquest\Table\Columns\Column;
use Conquest\Table\Sorts\ToggleSort;

it('can instantiate a column', function () {
    $col = new Column('name');
    expect($col)->toBeInstanceOf(Column::class)
        ->getName()->toBe('name')
        ->getLabel()->toBe('Name')
        ->getType()->toBeNull()
        ->isSortable()->toBeFalse()
        ->isSearchable()->toBeFalse()
        ->hasSearchProperty()->toBeFalse()
        ->isToggleable()->toBeFalse()
        ->isToggledOn()->toBeTrue()
        ->hasBreakpoint()->toBeFalse()
        ->isAuthorized()->toBeTrue()
        ->hasFallback()->toBeFalse()
        ->isHidden()->toBeFalse()
        ->isSrOnly()->toBeFalse()
        ->canTransform()->toBeFalse()
        ->hasMeta()->toBeFalse()
        ->isKey()->toBeFalse()
        ->hasPrefix()->toBeFalse()
        ->hasSuffix()->toBeFalse()
        ->hasTooltip()->toBeFalse()
        ->hasPlaceholder()->toBeFalse()
        ->formatsBoolean()->toBeFalse()
        ->formatsMoney()->toBeFalse()
        ->formatsNumeric()->toBeFalse()
        ->formatsSeparator()->toBeFalse();
});

describe('base make', function () {
    beforeEach(function () {
        $this->col = Column::make('name');
    });

    it('can make a column', function () {
        expect($this->col)->toBeInstanceOf(Column::class)
            ->getName()->toBe('name')
            ->getLabel()->toBe('Name')
            ->getType()->toBeNull()
            ->isSortable()->toBeFalse()
            ->isSearchable()->toBeFalse()
            ->hasSearchProperty()->toBeFalse()
            ->isToggleable()->toBeFalse()
            ->isToggledOn()->toBeTrue()
            ->hasBreakpoint()->toBeFalse()
            ->isAuthorized()->toBeTrue()
            ->hasFallback()->toBeFalse()
            ->isHidden()->toBeFalse()
            ->isSrOnly()->toBeFalse()
            ->canTransform()->toBeFalse()
            ->hasMeta()->toBeFalse()
            ->isKey()->toBeFalse()
            ->hasPrefix()->toBeFalse()
            ->hasSuffix()->toBeFalse()
            ->hasTooltip()->toBeFalse()
            ->hasPlaceholder()->toBeFalse()
            ->formatsBoolean()->toBeFalse()
            ->formatsMoney()->toBeFalse()
            ->formatsNumeric()->toBeFalse()
            ->formatsSeparator()->toBeFalse();
    });

    it('has array form', function () {
        expect($this->col->toArray())->toEqual([
            'name' => 'name',
            'label' => 'Name',
            'type' => null,
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
        $this->col = Column::make('name')
            ->label('Username')
            ->sortable('uuid')
            ->searchable('description')
            ->toggleable(false)
            ->breakpoint('md')
            ->authorize(fn () => false)
            ->fallback('N/A')
            ->tooltip('Tooltip')
            ->placeholder('No name')
            ->hidden()
            ->srOnly()
            ->transform(fn ($value) => mb_strtoupper($value))
            ->key()
            ->money()
            ->boolean()
            ->numeric()
            ->separator()
            ->meta(['key' => 'value'])
            ->suffix('.')
            ->prefix('Mr ');
    });

    it('can chain methods on a column', function () {
        expect($this->col)->toBeInstanceOf(Column::class)
            ->getName()->toBe('name')
            ->getLabel()->toBe('Username')
            ->getType()->toBeNull()
            ->isSortable()->toBeTrue()
            ->getSort()->toBeInstanceOf(ToggleSort::class)
            ->isSearchable()->toBeTrue()
            ->hasSearchProperty()->toBeTrue()
            ->getSearchProperty()->toBe('description')
            ->isToggleable()->toBeTrue()
            ->isToggledOn()->toBeFalse()
            ->hasBreakpoint()->toBeTrue()
            ->getBreakpoint()->toBe('md')
            ->isAuthorized()->toBeFalse()
            ->hasFallback()->toBeTrue()
            ->getFallback()->toBe('N/A')
            ->isHidden()->toBeTrue()
            ->isSrOnly()->toBeTrue()
            ->canTransform()->toBeTrue()
            ->hasMeta()->toBeTrue()
            ->getMeta()->toBe(['key' => 'value'])
            ->isKey()->toBeTrue()
            ->hasPrefix()->toBeTrue()
            ->getPrefix()->toBe('Mr ')
            ->hasSuffix()->toBeTrue()
            ->getSuffix()->toBe('.')
            ->hasTooltip()->toBeTrue()
            ->getTooltip()->toBe('Tooltip')
            ->hasPlaceholder()->toBeTrue()
            ->getPlaceholder()->toBe('No name')
            ->formatsBoolean()->toBeTrue()
            ->formatsMoney()->toBeTrue()
            ->formatsNumeric()->toBeTrue()
            ->formatsSeparator()->toBeTrue();
    });

    it('has array form', function () {
        expect($this->col->toArray())->toEqual([
            'name' => 'name',
            'label' => 'Username',
            'type' => null,
            'hidden' => true,
            'placeholder' => 'No name',
            'tooltip' => 'Tooltip',
            'breakpoint' => 'md',
            'sr' => true,
            'toggleable' => true,
            'active' => false,
            'sortable' => true,
            'sorting' => false,
            'direction' => null,
            'meta' => ['key' => 'value'],
            'prefix' => 'Mr ',
            'suffix' => '.',
        ]);
    });
});

it('does not allow column name to be actions from instantiation', function () {
    $col = new Column('actions');
})->throws(InvalidArgumentException::class, 'Column name cannot be "actions"');

it('does not allow column name to be actions from make', function () {
    $col = Column::make('actions');
})->throws(InvalidArgumentException::class, 'Column name cannot be "actions"');

it('can apply a column and fallbacks with value', function () {
    $col = Column::make('name')->fallback('Exists');
    expect($col->apply(null))->toBe('Exists');
});

it('can apply a column and transforms value', function () {
    $col = Column::make('name')->transform(fn ($value) => strtoupper($value));
    expect($col->apply('a'))->toBe('A');
});

it('can apply a column and formats boolean value', function () {
    $col = Column::make('name')->boolean();
    expect($col->apply(true))->toBe('Yes');
    expect($col->apply(false))->toBe('No');
});

it('can apply a column and formats money value', function () {
    $col = Column::make('name')->money();
    expect($col->apply(1000))->toBe('$1,000.00');
});

it('can apply a column and formats numeric value', function () {
    $col = Column::make('name')->numeric(0);
    expect($col->apply(1000.99))->toBe('1,001');
});

it('can apply a column and formats separator value', function () {
    $col = Column::make('name')->separator();
    expect($col->apply(['a', 'b']))->toBe('a, b');
});

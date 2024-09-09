<?php

use Conquest\Table\Columns\NumericColumn;
use Conquest\Table\Sorts\ToggleSort;

it('can instantiate a numeric column', function () {
    $col = new NumericColumn('amount');
    expect($col)->toBeInstanceOf(NumericColumn::class)
        ->getName()->toBe('amount')
        ->getLabel()->toBe('Amount')
        ->getType()->toBe('numeric')
        ->isSortable()->toBeFalse()
        ->isToggleable()->toBeFalse()
        ->isToggledOn()->toBeTrue()
        ->hasBreakpoint()->toBeFalse()
        ->isAuthorized()->toBeTrue()
        ->hasFallback()->toBeTrue()
        ->getFallback()->toBe(config('table.fallback.numeric'))
        ->isHidden()->toBeFalse()
        ->isSrOnly()->toBeFalse()
        ->canTransform()->toBeFalse()
        ->hasMeta()->toBeFalse()
        ->isKey()->toBeFalse()
        ->hasPrefix()->toBeFalse()
        ->hasSuffix()->toBeFalse()
        ->hasTooltip()->toBeFalse()
        ->hasPlaceholder()->toBeFalse()
        ->formatsMoney()->toBeFalse()
        ->formatsNumeric()->toBeFalse();
});

describe('base make', function () {
    beforeEach(function () {
        $this->col = NumericColumn::make('amount');
    });

    it('can make a numeric column', function () {
        expect($this->col)->toBeInstanceOf(NumericColumn::class)
            ->getName()->toBe('amount')
            ->getLabel()->toBe('Amount')
            ->getType()->toBe('numeric')
            ->isSortable()->toBeFalse()
            ->isToggleable()->toBeFalse()
            ->isToggledOn()->toBeTrue()
            ->hasBreakpoint()->toBeFalse()
            ->isAuthorized()->toBeTrue()
            ->hasFallback()->toBeTrue()
            ->getFallback()->toBe(config('table.fallback.numeric'))
            ->isHidden()->toBeFalse()
            ->isSrOnly()->toBeFalse()
            ->canTransform()->toBeFalse()
            ->hasMeta()->toBeFalse()
            ->isKey()->toBeFalse()
            ->hasPrefix()->toBeFalse()
            ->hasSuffix()->toBeFalse()
            ->hasTooltip()->toBeFalse()
            ->hasPlaceholder()->toBeFalse()
            ->formatsMoney()->toBeFalse()
            ->formatsNumeric()->toBeFalse();
    });

    it('has array form', function () {
        expect($this->col->toArray())->toEqual([
            'name' => 'amount',
            'label' => 'Amount',
            'type' => 'numeric',
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
        $this->col = NumericColumn::make('amount')
            ->label('Paid')
            ->sortable('amount')
            ->toggleable(false)
            ->breakpoint('md')
            ->authorize(fn () => false)
            ->fallback('N/A')
            ->tooltip('Tooltip')
            ->placeholder('No amount')
            ->hidden()
            ->srOnly()
            ->transform(fn ($value) => mb_strtoupper($value))
            ->key()
            ->meta(['key' => 'value'])
            ->suffix('.')
            ->prefix('@')
            ->money()
            ->numeric();
    });

    it('can chain methods on a numeric column', function () {
        expect($this->col)->toBeInstanceOf(NumericColumn::class)
            ->getName()->toBe('amount')
            ->getLabel()->toBe('Paid')
            ->getType()->toBe('numeric')
            ->isSortable()->toBeTrue()
            ->getSort()->toBeInstanceOf(ToggleSort::class)
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
            ->getPrefix()->toBe('@')
            ->hasSuffix()->toBeTrue()
            ->getSuffix()->toBe('.')
            ->hasTooltip()->toBeTrue()
            ->getTooltip()->toBe('Tooltip')
            ->hasPlaceholder()->toBeTrue()
            ->getPlaceholder()->toBe('No amount')
            ->formatsMoney()->toBeTrue()
            ->formatsNumeric()->toBeTrue();
    });

    it('has array form', function () {
        expect($this->col->toArray())->toEqual([
            'name' => 'amount',
            'label' => 'Paid',
            'type' => 'numeric',
            'hidden' => true,
            'placeholder' => 'No amount',
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

it('does not allow column name to be actions from instantiation', function () {
    $col = new NumericColumn('actions');
})->throws(InvalidArgumentException::class, 'Column name cannot be "actions"');

it('does not allow column name to be actions from make', function () {
    $col = NumericColumn::make('actions');
})->throws(InvalidArgumentException::class, 'Column name cannot be "actions"');

it('can apply a numeric column and fallbacks with numeric fallback', function () {
    $col = NumericColumn::make('amount');
    expect($col->apply(null))->toBe(config('table.fallback.numeric'));
});

it('can apply a numeric column and transforms value', function () {
    $col = NumericColumn::make('amount')->transform(fn ($value) => strtoupper($value));
    expect($col->apply('a'))->toBe('A');
});

it('can apply a numeric column and formats money value', function () {
    $col = NumericColumn::make('amount')->money();
    expect($col->apply(1000))->toBe('$1,000.00');
});

it('can apply a numeric column and formats numeric value', function () {
    $col = NumericColumn::make('amount')->numeric(0);
    expect($col->apply(1000.99))->toBe('1,001');
});

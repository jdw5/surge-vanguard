<?php

use Carbon\Carbon;
use Conquest\Table\Columns\DateColumn;
use Conquest\Table\Sorts\ToggleSort;

beforeEach(function () {
    Carbon::setTestNow('2000-01-01 00:00:00');
});

it('can instantiate a date column', function () {
    $col = new DateColumn('created_at');
    expect($col)->toBeInstanceOf(DateColumn::class)
        ->getName()->toBe('created_at')
        ->getLabel()->toBe('Created at')
        ->getType()->toBe('date')
        ->isSortable()->toBeFalse()
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
        ->hasFormat()->toBeFalse()
        ->formatsSince()->toBeFalse();
});

describe('base make', function () {
    beforeEach(function () {
        $this->col = DateColumn::make('created_at');
    });

    it('can make a date column', function () {
        expect($this->col)->toBeInstanceOf(DateColumn::class)
            ->getName()->toBe('created_at')
            ->getLabel()->toBe('Created at')
            ->getType()->toBe('date')
            ->isSortable()->toBeFalse()
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
            ->hasFormat()->toBeFalse()
            ->formatsSince()->toBeFalse();
    });

    it('has array form', function () {
        expect($this->col->toArray())->toEqual([
            'name' => 'created_at',
            'label' => 'Created at',
            'type' => 'date',
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
        $this->col = DateColumn::make('created_at')
            ->label('When')
            ->sortable()
            ->toggleable(false)
            ->breakpoint('md')
            ->authorize(fn () => false)
            ->fallback('nil')
            ->tooltip('Tooltip')
            ->placeholder('No date')
            ->hidden()
            ->srOnly()
            ->transform(fn ($value) => mb_strtoupper($value))
            ->key()
            ->meta(['key' => 'value'])
            ->suffix('.')
            ->prefix('@')
            ->format('Y-m-d H:i:s')
            ->since();
    });

    it('can chain methods on a date column', function () {
        expect($this->col)->toBeInstanceOf(DateColumn::class)
            ->getName()->toBe('created_at')
            ->getLabel()->toBe('When')
            ->getType()->toBe('date')
            ->isSortable()->toBeTrue()
            ->getSort()->toBeInstanceOf(ToggleSort::class)
            ->isToggleable()->toBeTrue()
            ->isToggledOn()->toBeFalse()
            ->hasBreakpoint()->toBeTrue()
            ->getBreakpoint()->toBe('md')
            ->isAuthorized()->toBeFalse()
            ->hasFallback()->toBeTrue()
            ->getFallback()->toBe('nil')
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
            ->getPlaceholder()->toBe('No date')
            ->formatsSince()->toBeTrue()
            ->hasFormat()->toBeTrue()
            ->getFormat()->toBe('Y-m-d H:i:s');
    });

    it('has array form', function () {
        expect($this->col->toArray())->toEqual([
            'name' => 'created_at',
            'label' => 'When',
            'type' => 'date',
            'hidden' => true,
            'placeholder' => 'No date',
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

it('does not allow date column name to be actions from instantiation', function () {
    $col = new DateColumn('actions');
})->throws(InvalidArgumentException::class, 'Column name cannot be "actions"');

it('does not allow date column name to be actions from make', function () {
    $col = DateColumn::make('actions');
})->throws(InvalidArgumentException::class, 'Column name cannot be "actions"');

it('can apply a date column and fallbacks with value', function () {
    $col = DateColumn::make('created_at')->fallback('Exists');
    expect($col->apply(null))->toBe('Exists');
});

it('can apply a date column and transforms value', function () {
    $col = DateColumn::make('created_at')->transform(fn ($value) => strtoupper($value));
    expect($col->apply('a'))->toBe('A');
});

it('can apply a date column and uses format', function () {
    $col = DateColumn::make('created_at')->format('Y-m-d H:i:s');
    expect($col->apply('2021-01-01'))->toBe('2021-01-01 00:00:00');
});

it('can apply a date column and formats since value', function () {
    $col = DateColumn::make('created_at')->since();
    expect($col->apply(now()->subDay()))->toBe('1 day ago');
});

it('catches invalid format exceptions on since and provides fallback', function () {
    $col = DateColumn::make('created_at')->fallback('-')->since();
    expect($col->apply('Invalid'))->toBe('-');
});

it('catches invalid format exceptions on since and continues', function () {
    $col = DateColumn::make('created_at')->since();
    expect($col->apply('Invalid'))->toBe('Invalid');
});

it('catches invalid format exceptions on format and provides fallback', function () {
    $col = DateColumn::make('created_at')->fallback('-')->format('Y-m-d H:i:s');
    expect($col->apply('Invalid'))->toBe('-');
});

it('catches invalid format exceptions on format and continues', function () {
    $col = DateColumn::make('created_at')->format('Y-m-d H:i:s');
    expect($col->apply('Invalid'))->toBe('Invalid');
});

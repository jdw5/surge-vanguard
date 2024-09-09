<?php

use Conquest\Table\Columns\TextColumn;
use Conquest\Table\Sorts\ToggleSort;

it('can instantiate a text column', function () {
    $col = new TextColumn('name');
    expect($col)->toBeInstanceOf(TextColumn::class)
        ->getName()->toBe('name')
        ->getLabel()->toBe('Name')
        ->getType()->toBe('text')
        ->isSortable()->toBeFalse()
        ->isSearchable()->toBeFalse()
        ->hasSearchProperty()->toBeFalse()
        ->isToggleable()->toBeFalse()
        ->isToggledOn()->toBeTrue()
        ->hasBreakpoint()->toBeFalse()
        ->isAuthorized()->toBeTrue()
        ->hasFallback()->toBeTrue()
        ->getFallback()->toBe(config('table.fallback.text'))
        ->isHidden()->toBeFalse()
        ->isSrOnly()->toBeFalse()
        ->canTransform()->toBeFalse()
        ->hasMeta()->toBeFalse()
        ->isKey()->toBeFalse()
        ->hasPrefix()->toBeFalse()
        ->hasSuffix()->toBeFalse()
        ->hasTooltip()->toBeFalse()
        ->hasPlaceholder()->toBeFalse();
});

describe('base make', function () {
    beforeEach(function () {
        $this->col = TextColumn::make('name');
    });

    it('can make a text column', function () {
        expect($this->col)->toBeInstanceOf(TextColumn::class)
            ->getName()->toBe('name')
            ->getLabel()->toBe('Name')
            ->getType()->toBe('text')
            ->isSortable()->toBeFalse()
            ->isSearchable()->toBeFalse()
            ->hasSearchProperty()->toBeFalse()
            ->isToggleable()->toBeFalse()
            ->isToggledOn()->toBeTrue()
            ->hasBreakpoint()->toBeFalse()
            ->isAuthorized()->toBeTrue()
            ->hasFallback()->toBeTrue()
            ->getFallback()->toBe(config('table.fallback.text'))
            ->isHidden()->toBeFalse()
            ->isSrOnly()->toBeFalse()
            ->canTransform()->toBeFalse()
            ->hasMeta()->toBeFalse()
            ->isKey()->toBeFalse()
            ->hasPrefix()->toBeFalse()
            ->hasSuffix()->toBeFalse()
            ->hasTooltip()->toBeFalse()
            ->hasPlaceholder()->toBeFalse();
    });

    it('has array form', function () {
        expect($this->col->toArray())->toEqual([
            'name' => 'name',
            'label' => 'Name',
            'type' => 'text',
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
        $this->col = TextColumn::make('name')
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
            ->meta(['key' => 'value'])
            ->suffix('.')
            ->prefix('Mr ');
    });

    it('can chain methods on a text column', function () {
        expect($this->col)->toBeInstanceOf(TextColumn::class)
            ->getName()->toBe('name')
            ->getLabel()->toBe('Username')
            ->getType()->toBe('text')
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
            ->getPlaceholder()->toBe('No name');
    });

    it('has array form', function () {
        expect($this->col->toArray())->toEqual([
            'name' => 'name',
            'label' => 'Username',
            'type' => 'text',
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
    $col = new TextColumn('actions');
})->throws(InvalidArgumentException::class, 'Column name cannot be "actions"');

it('does not allow column name to be actions from make', function () {
    $col = TextColumn::make('actions');
})->throws(InvalidArgumentException::class, 'Column name cannot be "actions"');

it('can apply a text column and fallbacks with text fallback', function () {
    $col = TextColumn::make('name');
    expect($col->apply(null))->toBe(config('table.fallback.text'));
});

it('can apply a text column and transforms value', function () {
    $col = TextColumn::make('name')->transform(fn ($value) => strtoupper($value));
    expect($col->apply('a'))->toBe('A');
});

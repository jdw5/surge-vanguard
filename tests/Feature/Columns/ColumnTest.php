<?php

use Conquest\Table\Columns\BooleanColumn;
use Conquest\Table\Columns\Column;
use Conquest\Table\Columns\DateColumn;
use Conquest\Table\Columns\Enums\Breakpoint;
use Conquest\Table\Columns\NumericColumn;
use Conquest\Table\Columns\TextColumn;

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
        ->isToggledOn()->toBeTrue()
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
        ->isToggledOn()->toBeTrue()
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
        isKey: true,
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
        ->isToggledOn()->toBeFalse()
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
        ->isToggledOn()->toBeTrue()
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

// it('can make and apply a boolean column', function () {
//     $col = BooleanColumn::make('active')->truthLabel($t = 'Active')->falseLabel($f = 'Inactive');
//     expect($col->getType())->toBe('col:boolean');
//     expect($col->getTruthLabel())->toBe($t);
//     expect($col->getFalseLabel())->toBe($f);
//     expect($col->apply(true))->toBe($t);
//     expect($col->apply(false))->toBe($f);
// });

// it('can make and apply a date column', function () {
//     $col = DateColumn::make('created_at')->format('d M Y');

//     expect($col->getType())->toBe('col:date');
//     expect($col->apply('01-01-2001'))->toBe('01 Jan 2001');

// });

// it('can make a text column', function () {
//     $col = TextColumn::make('name');
//     expect($col->getFallback())->toBe('-');
//     expect($col->getType())->toBe('col:text');
// });

// it('can make a numeric column', function () {
//     $col = NumericColumn::make('price');
//     expect($col->getFallback())->toBe(0);
//     expect($col->getType())->toBe('col:numeric');
// });

// it('transforms a column value', function () {
//     $col = Column::make('count')->transform(fn ($value) => $value - 2);
//     expect($col->transformUsing(5))->toBe(3);
// });
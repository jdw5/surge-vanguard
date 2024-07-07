<?php

use Conquest\Table\Columns\BooleanColumn;
use Conquest\Table\Columns\Column;
use Conquest\Table\Columns\DateColumn;
use Conquest\Table\Columns\Enums\Breakpoint;
use Conquest\Table\Columns\NumericColumn;
use Conquest\Table\Columns\TextColumn;

it('can create simple column', function () {
    $col = new Column('name');
    expect($col->getName())->toBe('name');
    expect($col->getLabel())->toBe('Name');
    expect($col->hasSort())->toBeFalse();
    expect($col->isSearchable())->toBeFalse();
    expect($col->getBreakpoint())->toBeNull();
    expect($col->isAuthorized())->toBeTrue();
    expect($col->getFallback())->toBeNull();
    expect($col->isHidden())->toBeFalse();
    expect($col->isSrOnly())->toBeFalse();
    expect($col->canTransform())->toBeFalse();
    expect($col->isActive())->toBeTrue();
});

it('can create column with arguments', function () {
    $col = new Column(
        name: 'name',
        label: $label = 'Username',
        sortable: true,
        searchable: true,
        breakpoint: Breakpoint::XS,
        authorize: fn () => false,
        fallback: 'N/A',
        hidden: true,
        srOnly: true,
        transform: fn ($value) => strtoupper($value),
        active: false,
    );

    expect($col->getName())->toBe('name');
    expect($col->getLabel())->toBe($label);
    expect($col->hasSort())->toBeTrue();
    expect($col->isSearchable())->toBeTrue();
    expect($col->getBreakpoint())->toBe(Breakpoint::XS->value);
    expect($col->isAuthorized())->toBeFalse();
    expect($col->getFallback())->toBe('N/A');
    expect($col->isHidden())->toBeTrue();
    expect($col->isSrOnly())->toBeTrue();
    expect($col->canTransform())->toBeTrue();
    expect($col->isActive())->toBeFalse();

});

it('can make a column', function () {
    $col = Column::make('name');
    expect($col->getName())->toBe('name');
    expect($col->getLabel())->toBe('Name');

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
    
    expect($col->getName())->toBe('name');
    expect($col->getLabel())->toBe($label);
    expect($col->hasSort())->toBeTrue();
    expect($col->isSearchable())->toBeTrue();
    expect($col->getBreakpoint())->toBe(Breakpoint::XS->value);
    expect($col->isAuthorized())->toBeFalse();
    expect($col->getFallback())->toBe('N/A');
    expect($col->isHidden())->toBeTrue();
    expect($col->isSrOnly())->toBeTrue();
    expect($col->canTransform())->toBeTrue();
});

it('can apply a column and fallbacks with value', function () {
    $fn = fn ($value) => strtoupper($value);
    $col = Column::make('name')->fallback('Exists')->transform($fn);

    expect($col->apply('test'))->toBe('TEST');
    expect($col->apply(null))->toBe('Exists');
});

it('can make and apply a boolean column', function () {
    $col = BooleanColumn::make('active')->truthLabel($t = 'Active')->falseLabel($f = 'Inactive');
    expect($col->getType())->toBe('col:boolean');
    expect($col->getTruthLabel())->toBe($t);
    expect($col->getFalseLabel())->toBe($f);
    expect($col->apply(true))->toBe($t);
    expect($col->apply(false))->toBe($f);
});

it('can make and apply a date column', function () {
    $col = DateColumn::make('created_at')->format('d M Y');

    expect($col->getType())->toBe('col:date');
    expect($col->apply('01-01-2001'))->toBe('01 Jan 2001');

});

it('can make a text column', function () {
    $col = TextColumn::make('name');
    expect($col->getFallback())->toBe('-');
    expect($col->getType())->toBe('col:text');
});

it('can make a numeric column', function () {
    $col = NumericColumn::make('price');
    expect($col->getFallback())->toBe(0);
    expect($col->getType())->toBe('col:numeric');
});

it('transforms a column value', function () {
    $col = Column::make('count')->transform(fn ($value) => $value - 2);
    expect($col->transformUsing(5))->toBe(3);
});
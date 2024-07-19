<?php

use Workbench\App\Models\Product;
use Conquest\Table\Filters\Filter;
use Conquest\Table\Filters\Enums\Clause;
use Conquest\Table\Filters\Enums\Operator;
use Conquest\Table\Sorts\IsotropicSort;
use Conquest\Table\Sorts\Sort;
use Conquest\Table\Table;
use Illuminate\Support\Facades\DB;

it('can create a sort', function () {
    $sort = new Sort($n = 'name');
    expect($sort->getProperty())->toBe($n);
    expect($sort->getName())->toBe($n);
    expect($sort->getLabel())->toBe('Name');
    expect($sort->isAuthorised())->toBeTrue();
    expect($sort->hasDirection())->toBeFalse();
    expect($sort->isDefault())->toBeFalse();
    expect($sort->hasMetadata())->toBeFalse();
});

it('can create a sort with arguments', function () {
    $sort = new Sort(
        property: 'name', 
        name: 'username',
        label: 'Name',
        authorize: false,
        direction: Table::ASCENDING,
        default: true,
        metadata: ['foo' => 'bar']
    );

    expect($sort->getProperty())->toBe('name');
    expect($sort->getName())->toBe('username');
    expect($sort->getLabel())->toBe('Name');
    expect($sort->isAuthorised())->toBeFalse();
    expect($sort->getDirection())->toBe('asc');
    expect($sort->isDefault())->toBeTrue();
    expect($sort->hasMetadata())->toBeTrue();
});

it('can make a sort', function () {
    expect(Sort::make('name'))
        ->toBeInstanceOf(Sort::class)
        ->getName()->toBe('name')
        ->getLabel()->toBe('Name');
});

it('can chain methods on a sort', function () {
    $sort = Sort::make('name')
        ->name('username')
        ->authorize(fn () => false)
        ->direction('asc')
        ->desc()
        ->default();
    
    expect($sort)
        ->getProperty()->toBe('name')
        ->getName()->toBe('username')
        ->getLabel()->toBe('Name')
        ->isAuthorised()->toBeFalse()
        ->getDirection()->toBe('desc')
        ->isDefault()->toBeTrue();
});

it('can apply a bidirectional sort to an eloquent builder', function () {
    $sort = Sort::make('name');
    $builder = Product::query();
    $sort->apply($builder, 'name', 'asc');
    expect($builder->toSql())->toBe('select * from "products" order by "products"."name" asc');
});

it('can apply a directional sort to an eloquent builder', function () {
    $sort = Sort::make('name')->desc();
    $builder = Product::query();
    $sort->apply($builder, 'name', 'desc');
    expect($builder->toSql())->toBe('select * from "products" order by "products"."name" desc');
});

it('can apply a bidirectional sort to a query builder', function () {
    $sort = Sort::make('name');
    $builder = DB::table('products');
    $sort->apply($builder, 'name', 'asc');
    expect($builder->toSql())->toBe('select * from "products" order by "name" asc');
});

it('can apply a directional sort to a query builder', function () {
    $sort = Sort::make('name')->desc();
    $builder = DB::table('products');
    $sort->apply($builder, 'name', 'desc');
    expect($builder->toSql())->toBe('select * from "products" order by "name" desc');
});

it('does not apply sort if name does not match', function () {
    $sort = Sort::make('name')->desc();
    $builder = Product::query();
    $sort->apply($builder, 'nam', 'asc');
    expect($builder->toSql())->toBe('select * from "products"');
});

it('ensures sort is not active if it does not match', function () {
    $sort = Sort::make('name');
    $builder = Product::query();
    $sort->apply($builder, 'nam', 'asc');
    expect($sort->isActive())->toBeFalse();
});

it('checks if bidirectional sort is active', function () {
    $sort = Sort::make('name');
    $builder = Product::query();
    $sort->apply($builder, 'name', 'asc');
    expect($sort->isActive())->toBeTrue();
});

it('checks if directional sort is active', function () {
    $sort = Sort::make('name')->asc();
    $builder = Product::query();
    $sort->apply($builder, 'name', 'asc');
    expect($sort->isActive())->toBeTrue();
});

it('ensures directional sort is not active if name does not match', function () {
    $sort = Sort::make('name')->asc();
    $builder = Product::query();
    $sort->apply($builder, 'nam', 'asc');
    expect($sort->isActive())->toBeFalse();
});

it('ensures directional sort is not active if direction does not match', function () {
    $sort = Sort::make('name')->asc();
    $builder = Product::query();
    $sort->apply($builder, 'name', 'desc');
    expect($sort->isActive())->toBeFalse();
});
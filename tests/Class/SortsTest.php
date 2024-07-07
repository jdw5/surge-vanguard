<?php

use Workbench\App\Models\Product;
use Conquest\Table\Filters\Filter;
use Conquest\Table\Filters\Enums\Clause;
use Conquest\Table\Filters\Enums\Operator;
use Conquest\Table\Sorts\Sort;
use Illuminate\Support\Facades\DB;

it('can create a sort', function () {
    $sort = new Sort($n = 'name');
    expect($sort->getProperty())->toBe($n);
    expect($sort->getName())->toBe($n);
    expect($sort->getLabel())->toBe('Name');
    expect($sort->isAuthorised())->toBeTrue();
    expect($sort->getDirection())->toBe('asc'); // Default direction
    expect($sort->isDefault())->toBeFalse();
});

it('can create a sort with arguments', function () {
    $sort = new Sort('name', 
        name: 'username',
        authorize: false,
        direction: 'asc',
        default: true,
    );

    expect($sort->getProperty())->toBe('name');
    expect($sort->getName())->toBe('username');
    expect($sort->getLabel())->toBe('Username');
    expect($sort->isAuthorised())->toBeFalse();
    expect($sort->getDirection())->toBe('asc');
    expect($sort->isDefault())->toBeTrue();
});

it('can make a sort', function () {
    $sort = Sort::make('name');
    expect($sort->getProperty())->toBe('name');
    expect($sort->getLabel())->toBe('Name');
});

it('can chain methods on a sort', function () {
    $sort = Sort::make('name')
        ->name('username')
        ->authorize(fn () => false)
        ->direction('asc')
        ->desc()
        ->default();
    
    expect($sort->getProperty())->toBe('name');
    expect($sort->getName())->toBe('username');
    expect($sort->getLabel())->toBe('Name'); // Uses property to generate label
    expect($sort->isAuthorised())->toBeFalse();
    expect($sort->getDirection())->toBe('desc');
    expect($sort->isDefault())->toBeTrue();
});

it('can apply a sort to an eloquent builder', function () {
    $sort = Sort::make('name')->desc();
    $builder = Product::query();
    request()->merge(['sort' => 'name']);
    $sort->apply($builder);
    expect($builder->toSql())->toBe('select * from "products" order by "products"."name" desc');
});

it('can apply a sort to a query builder', function () {
    $sort = Sort::make('name')->desc();
    $builder = DB::table('products');
    request()->merge(['sort' => 'name']);
    $sort->apply($builder);
    expect($builder->toSql())->toBe('select * from "products" order by "name" desc');
});

// it('can apply a filter to a query builder', function () {
//     $filter = Filter::make('name');
//     $builder = DB::table('products');
//     request()->merge(['name' => 'test']);
//     $filter->apply($builder);
//     expect($builder->toSql())->toBe('select * from "products" where "name" = ?');
// });


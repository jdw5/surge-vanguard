<?php

use Workbench\App\Models\Product;
use Conquest\Table\Filters\Filter;
use Conquest\Table\Filters\Enums\Clause;
use Conquest\Table\Filters\Enums\Operator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

it('can create a filter', function () {
    $filter = new Filter($n = 'name');
    expect($filter)->getProperty()->toBe($n)
        ->getName()->toBe($n)
        ->getLabel()->toBe('Name')
        ->isAuthorised()->toBeTrue()
        ->getClause()->toBe(Clause::IS)
        ->getOperator()->toBe(Operator::EQUAL);
});

it('can create a filter with arguments', function () {
    $filter = new Filter('name', 
        name: 'username',
        authorize: fn () => false,
        clause: Clause::IS_NOT,
        operator: Operator::NOT_EQUAL,
    );

    expect($filter)->getProperty()->toBe('name')
        ->getName()->toBe('username')
        ->getLabel()->toBe('Username')
        ->isAuthorised()->toBeFalse()
        ->getClause()->toBe(Clause::IS_NOT)
        ->getOperator()->toBe(Operator::NOT_EQUAL);
});

it('can make a filter', function () {
    $filter = Filter::make('name');
    expect($filter->getProperty())->toBe('name');
    expect($filter->getLabel())->toBe('Name');
});

it('can chain methods on a filter', function () {
    $filter = Filter::make('name')
        ->name('username')
        ->authorize(fn () => false)
        ->clause(Clause::IS_NOT)
        ->operator(Operator::NOT_EQUAL);
    
    expect($filter)->getProperty()->toBe('name')
        ->getName()->toBe('username')
        ->getLabel()->toBe('Name')
        ->isAuthorised()->toBeFalse()
        ->getClause()->toBe(Clause::IS_NOT)
        ->getOperator()->toBe(Operator::NOT_EQUAL);
});

it('can apply a filter to an eloquent builder', function () {
    $filter = Filter::make('name');
    $builder = Product::query();
    // Requires a request to work
    Request::merge(['name' => 'test']);
    $filter->apply($builder);
    expect($builder->toSql())->toBe('select * from "products" where "name" = ?');
});

it('can apply a filter to a query builder', function () {
    $filter = Filter::make('name');
    $builder = DB::table('products')  ;
    Request::merge(['name' => 'test']);
    $filter->apply($builder);
    expect($builder->toSql())->toBe('select * from "products" where "name" = ?');
    expect($filter->isActive())->toBeTrue();
});
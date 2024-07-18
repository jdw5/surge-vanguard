<?php

use Workbench\App\Models\Product;
use Conquest\Table\Filters\Filter;
use Conquest\Table\Filters\Enums\Clause;
use Conquest\Table\Filters\Enums\Operator;
use Illuminate\Support\Facades\DB;

it('can create a filter', function () {
    $filter = new Filter($n = 'name');
    expect($filter->getProperty())->toBe($n);
    expect($filter->getName())->toBe($n);
    expect($filter->getLabel())->toBe('Name');
    expect($filter->isAuthorised())->toBeTrue();
    expect($filter->getClause())->toBe(Clause::IS);
    expect($filter->getOperator())->toBe(Operator::EQUAL);
    expect($filter->isNegated())->toBeFalse();
});

it('can create a filter with arguments', function () {
    $filter = new Filter('name', 
        name: 'username',
        authorize: fn () => false,
        clause: Clause::IS_NOT,
        operator: Operator::NOT_EQUAL,
        negate: true,
    );

    expect($filter->getProperty())->toBe('name');
    expect($filter->getName())->toBe('username');
    expect($filter->getLabel())->toBe('Username');
    expect($filter->isAuthorised())->toBeFalse();
    expect($filter->getClause())->toBe(Clause::IS_NOT);
    expect($filter->getOperator())->toBe(Operator::NOT_EQUAL);
    expect($filter->isNegated())->toBeTrue();
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
        ->operator(Operator::NOT_EQUAL)
        ->negate();
    
    expect($filter->getProperty())->toBe('name');
    expect($filter->getName())->toBe('username');
    expect($filter->getLabel())->toBe('Name'); // Uses property to generate label
    expect($filter->isAuthorised())->toBeFalse();
    expect($filter->getClause())->toBe(Clause::IS_NOT);
    expect($filter->getOperator())->toBe(Operator::NOT_EQUAL);
    expect($filter->isNegated())->toBeTrue();
});

it('can apply a filter to an eloquent builder', function () {
    $filter = Filter::make('name');
    $builder = Product::query();
    // Requires a request to work
    request()->merge(['name' => 'test']);
    $filter->apply($builder);
    expect($builder->toSql())->toBe('select * from "products" where "name" = ?');
});

it('can apply a filter to a query builder', function () {
    $filter = Filter::make('name');
    $builder = DB::table('products');
    request()->merge(['name' => 'test']);
    $filter->apply($builder);
    expect($builder->toSql())->toBe('select * from "products" where "name" = ?');
});


<?php

use Conquest\Table\Filters\Enums\Clause;
use Conquest\Table\Filters\Enums\Operator;
use Conquest\Table\Filters\Filter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Workbench\App\Models\Product;

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
    $builder = DB::table('products');
    Request::merge(['name' => 'test']);
    $filter->apply($builder);
    expect($builder->toSql())->toBe('select * from "products" where "name" = ?');
    expect($filter->isActive())->toBeTrue();
});

it('does not apply a filter if nothing in query', function () {
    $filter = Filter::make('name', 'n');
    $builder = Product::query();
    $filter->apply($builder);
    expect($builder->toSql())->toBe('select * from "products"');
    expect($filter->isActive())->toBeFalse();
});

it('does not apply a filter if name does not match', function () {
    $filter = Filter::make('name', 'n');
    $builder = Product::query();
    Request::merge(['name' => 'test']);
    $filter->apply($builder);
    expect($builder->toSql())->toBe('select * from "products"');
    expect($filter->isActive())->toBeFalse();
});

it('has array representation', function () {
    $filter = Filter::make('name');
    expect($filter->toArray())->toEqual([
        'name' => 'name',
        'label' => 'Name',
        'type' => 'filter',
        'active' => false,
        'value' => null,
        'meta' => [],
    ]);
});

it('changes array representation if filter applied', function () {
    $f = Filter::make('name');
    $f2 = Filter::make('description');
    Request::merge(['name' => 'test']);
    $builder = Product::query();
    $f->apply($builder);
    $f2->apply($builder);

    expect($f->isActive())->toBeTrue();
    expect($f2->isActive())->toBeFalse();
    expect($builder->toSql())->toBe('select * from "products" where "name" = ?');

    expect($f->toArray())->toEqual([
        'name' => 'name',
        'label' => 'Name',
        'type' => 'filter',
        'active' => true,
        'value' => 'test',
        'meta' => [],
    ]);

    expect($f2->toArray())->toEqual([
        'name' => 'description',
        'label' => 'Description',
        'type' => 'filter',
        'active' => false,
        'value' => null,
        'meta' => [],
    ]);
});

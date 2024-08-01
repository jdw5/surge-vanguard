<?php

use Conquest\Table\Filters\BooleanFilter;
use Conquest\Table\Filters\Enums\Clause;
use Conquest\Table\Filters\Enums\Operator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Workbench\App\Models\Product;

it('can create a boolean filter', function () {
    $filter = new BooleanFilter($n = 'name');
    expect($filter)->getProperty()->toBe($n)
        ->getName()->toBe($n)
        ->getLabel()->toBe('Name')
        ->getValue()->toBeTrue()
        ->isAuthorised()->toBeTrue()
        ->getClause()->toBe(Clause::IS)
        ->getOperator()->toBe(Operator::EQUAL)
        ->hasMeta()->toBeFalse();
});

it('can create a boolean filter with arguments', function () {
    $filter = new BooleanFilter(
        property: 'name',
        name: 'username',
        authorize: fn () => false,
        value: 5,
        clause: Clause::IS_NOT,
        operator: Operator::GREATER_THAN,
        meta: ['key' => 'value'],
    );

    expect($filter)->getProperty()->toBe('name')
        ->getName()->toBe('username')
        ->getLabel()->toBe('Username')
        ->getValue()->toBe(5)
        ->isAuthorised()->toBeFalse()
        ->getClause()->toBe(Clause::IS_NOT)
        ->getOperator()->toBe(Operator::GREATER_THAN)
        ->getMeta()->toBe(['key' => 'value']);
});

it('can make a boolean filter', function () {
    expect(BooleanFilter::make($n = 'name'))->toBeInstanceOf(BooleanFilter::class)
        ->getProperty()->toBe($n)
        ->getName()->toBe($n)
        ->getLabel()->toBe('Name')
        ->getValue()->toBeTrue()
        ->isAuthorised()->toBeTrue()
        ->getClause()->toBe(Clause::IS)
        ->getOperator()->toBe(Operator::EQUAL)
        ->hasMeta()->toBeFalse();
});

it('can chain methods on a boolean filter', function () {
    $filter = BooleanFilter::make('name')
        ->name('username')
        ->label('Enter name')
        ->value(10)
        ->authorize(fn () => false)
        ->isNot()
        ->gt()
        ->meta(['key' => 'value']);

    expect($filter)->toBeInstanceOf(BooleanFilter::class)
        ->getProperty()->toBe('name')
        ->getName()->toBe('username')
        ->getLabel()->toBe('Enter name')
        ->getValue()->toBe(10)
        ->isAuthorised()->toBeFalse()
        ->getClause()->toBe(Clause::IS_NOT)
        ->getOperator()->toBe(Operator::GREATER_THAN)
        ->getMeta()->toBe(['key' => 'value']);
});

it('can apply a base boolean filter to an eloquent builder using 1', function () {
    $filter = BooleanFilter::make('best_seller', 'favourite');
    $builder = Product::query();
    Request::merge(['favourite' => '1']);
    $filter->apply($builder);
    expect($builder->toSql())->toBe('select * from "products" where "best_seller" = ?');
    expect($filter->isActive())->toBeTrue();
});

it('can apply a base boolean filter to a query builder using 1', function () {
    $filter = BooleanFilter::make('best_seller', 'favourite');
    $builder = DB::table('products');
    Request::merge(['favourite' => '1']);
    $filter->apply($builder);
    expect($builder->toSql())->toBe('select * from "products" where "best_seller" = ?');
    expect($filter->isActive())->toBeTrue();
});

it('can apply a base boolean filter to a builder using true', function () {
    $filter = BooleanFilter::make('best_seller', 'favourite');
    $builder = Product::query();
    Request::merge(['favourite' => 'true']);
    $filter->apply($builder);
    expect($builder->toSql())->toBe('select * from "products" where "best_seller" = ?');
    expect($filter->isActive())->toBeTrue();
});

it('can apply a base boolean filter to a builder using yes', function () {
    $filter = BooleanFilter::make('best_seller', 'favourite');
    $builder = Product::query();
    Request::merge(['favourite' => 'yes']);
    $filter->apply($builder);
    expect($builder->toSql())->toBe('select * from "products" where "best_seller" = ?');
    expect($filter->isActive())->toBeTrue();
});

it('does not apply a boolean filter if name not provided', function () {
    $filter = BooleanFilter::make('best_seller', 'favourite');
    $builder = Product::query();
    $filter->apply($builder);
    expect($builder->toSql())->toBe('select * from "products"');
    expect($filter->isActive())->toBeFalse();
});

it('does not apply a boolean filter if name not equal', function () {
    $filter = BooleanFilter::make('best_seller', 'favourite');
    $builder = Product::query();
    Request::merge(['best_seller' => '1']);
    $filter->apply($builder);
    expect($builder->toSql())->toBe('select * from "products"');
    expect($filter->isActive())->toBeFalse();
});

it('does not apply a boolean filter if value not boolean', function () {
    $filter = BooleanFilter::make('best_seller', 'favourite');
    $builder = Product::query();
    Request::merge(['best_seller' => 'false']);
    $filter->apply($builder);
    expect($builder->toSql())->toBe('select * from "products"');
    expect($filter->isActive())->toBeFalse();
});

it('has array representation', function () {
    $filter = BooleanFilter::make('name');
    expect($filter->toArray())->toEqual([
        'name' => 'name',
        'label' => 'Name',
        'type' => 'filter:boolean',
        'active' => false,
        'meta' => [],
    ]);
});

it('changes array representation if boolean filter applied', function () {
    $f = BooleanFilter::make('price')->value(10);
    $f2 = BooleanFilter::make('best_seller', 'favourite');
    Request::merge(['price' => '1']);
    $builder = Product::query();
    $f->apply($builder);
    $f2->apply($builder);

    expect($f->isActive())->toBeTrue();
    expect($f2->isActive())->toBeFalse();
    expect($builder->toSql())->toBe('select * from "products" where "price" = ?');

    expect($f->toArray())->toEqual([
        'name' => 'price',
        'label' => 'Price',
        'type' => 'filter:boolean',
        'active' => true,
        'meta' => [],
    ]);

    expect($f2->toArray())->toEqual([
        'name' => 'favourite',
        'label' => 'Favourite',
        'type' => 'filter:boolean',
        'active' => false,
        'meta' => [],
    ]);
});

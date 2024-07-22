<?php

use Carbon\Carbon;
use Workbench\App\Models\Product;
use Illuminate\Support\Facades\DB;
use Conquest\Table\Filters\OperatorFilter;
use Illuminate\Support\Facades\Request;
use Conquest\Table\Filters\Enums\Operator;
use Conquest\Table\Filters\Enums\Clause;

it('can create a operator filter', function () {
    $filter = new OperatorFilter($n = 'name');
    expect($filter)->getType()->toBe('filter:operator')
        ->getProperty()->toBe($n)
        ->getName()->toBe($n)
        ->getLabel()->toBe('Name')
        ->isAuthorised()->toBeTrue()
        ->canValidate()->toBeFalse()
        ->canTransform()->toBeFalse()
        ->getClause()->toBe(Clause::IS)
        ->hasOperators()->toBeFalse()
        ->hasMetadata()->toBeFalse();
});

it('can create a operators filter with arguments', function () {
    $filter = new OperatorFilter('name', 
        name: 'username',
        authorize: fn () => false,
        validator: fn () => true,
        transform: fn ($value) => $value,
        clause: Clause::IS,
        operators: [
            Operator::EQUAL,
            Operator::NOT_EQUAL,
        ],
        metadata: ['key' => 'value'],
    );

    expect($filter)->getType()->toBe('filter:operator')
        ->getProperty()->toBe('name')
        ->getName()->toBe('username')
        ->getLabel()->toBe('Username')
        ->isAuthorised()->toBeFalse()
        ->canValidate()->toBeTrue()
        ->canTransform()->toBeTrue()
        ->getClause()->toBe(Clause::IS)
        ->hasOperators()->toBeTrue()
        ->getOperators()->toHaveLength(2)
        ->getMetadata()->toBe(['key' => 'value']);
});

it('can make am operator filter', function () {
    expect(OperatorFilter::make($n = 'name'))->toBeInstanceOf(OperatorFilter::class)
        ->getType()->toBe('filter:operator')
        ->getProperty()->toBe($n)
        ->getName()->toBe($n)
        ->getLabel()->toBe('Name')
        ->isAuthorised()->toBeTrue()
        ->canValidate()->toBeFalse()
        ->canTransform()->toBeFalse()
        ->getClause()->toBe(Clause::IS)
        ->hasOperators()->toBeFalse()
        ->hasMetadata()->toBeFalse();
});

it('can chain methods on an operator filter', function () {
    $filter = OperatorFilter::make('name')
        ->name('username')
        ->authorize(fn () => false)
        ->validator(fn () => true)
        ->transform(fn ($value) => $value)
        ->isNot()
        ->operators([
            Operator::EQUAL,
            Operator::NOT_EQUAL,
        ])
        ->metadata(['key' => 'value']);

    expect($filter)->toBeInstanceOf(OperatorFilter::class)
        ->getType()->toBe('filter:operator')
        ->getProperty()->toBe('name')
        ->getName()->toBe('username')
        ->getLabel()->toBe('Name')
        ->isAuthorised()->toBeFalse()
        ->canValidate()->toBeTrue()
        ->canTransform()->toBeTrue()
        ->getClause()->toBe(Clause::IS_NOT)
        ->hasOperators()->toBeTrue()
        ->getOperators()->toHaveLength(2)
        ->getMetadata()->toBe(['key' => 'value']);

});

it('can apply an operator filter to an eloquent builder', function () {
    $filter = OperatorFilter::make('name')->operators([Operator::EQUAL, Operator::NOT_EQUAL]);
    $builder = Product::query();
    Request::merge(['name' => 'name', '[name]' => '=']);
    $filter->apply($builder);
    expect($builder->toSql())->toBe('select * from "products" where "name" = ?');
    expect($filter->isActive())->toBeTrue();
});

it('can apply an operator filter to a query builder', function () {
    $filter = OperatorFilter::make('name')->operators([Operator::EQUAL, Operator::NOT_EQUAL]);
    $builder = DB::table('products');
    Request::merge(['name' => 'name', '[name]' => '=']);
    $filter->apply($builder);
    expect($builder->toSql())->toBe('select * from "products" where "name" = ?');
    expect($filter->isActive())->toBeTrue();
});

it('does not apply an operator filter if name or operator not provided', function () {
    $filter = OperatorFilter::make('name')->operators([Operator::EQUAL, Operator::NOT_EQUAL]);
    $builder = Product::query();
    $filter->apply($builder);
    expect($builder->toSql())->toBe('select * from "products"');
    expect($filter->isActive())->toBeFalse();
});

it('does not apply a operator filter if name not provided', function () {
    $filter = OperatorFilter::make('name')->operators([Operator::EQUAL, Operator::NOT_EQUAL]);
    $builder = Product::query();
    Request::merge(['[name]' => '=']);
    $filter->apply($builder);
    expect($builder->toSql())->toBe('select * from "products"');
    expect($filter->isActive())->toBeFalse();
});

it('does not apply a operator filter if operator not provided', function () {
    $filter = OperatorFilter::make('name')->operators([Operator::EQUAL, Operator::NOT_EQUAL]);
    $builder = Product::query();
    Request::merge(['name' => 'name']);
    $filter->apply($builder);
    expect($builder->toSql())->toBe('select * from "products"');
    expect($filter->isActive())->toBeFalse();
});

it('does not apply a operator filter if operator is outside operators', function () {
    $filter = OperatorFilter::make('name')->operators([Operator::EQUAL, Operator::NOT_EQUAL]);
    $builder = Product::query();
    Request::merge(['name' => 'name', '[name]' => '>=']);
    $filter->apply($builder);
    expect($builder->toSql())->toBe('select * from "products"');
    expect($filter->isActive())->toBeFalse();
});

it('has array representation', function () {
    $filter = OperatorFilter::make('name');
    expect($filter->toArray())->toEqual([
        'name' => 'name',
        'label' => 'Name',
        'type' => 'filter:operator',
        'active' => false,
        'value' => null,
        'metadata' => [],
        'operators' => []
    ]);
});

it('changes array representation if operator filter applied', function () {
    $f = OperatorFilter::make('name')->operators([Operator::EQUAL, Operator::NOT_EQUAL]);
    $f2 = OperatorFilter::make('description')->operators([Operator::EQUAL, Operator::NOT_EQUAL]);
    Request::merge(['name' => 'name', '[name]' => '=']);
    $builder = Product::query();
    $f->apply($builder);
    $f2->apply($builder);

    expect($f->isActive())->toBeTrue();
    expect($f2->isActive())->toBeFalse();
    expect($builder->toSql())->toBe('select * from "products" where "name" = ?');

    expect($f->toArray())->toEqual([
        'name' => 'name',
        'label' => 'Name',
        'type' => 'filter:operator',
        'active' => true,
        'value' => 'name',
        'operators' => [
            [
                'value' => '=',
                'label' => 'Equal to',
                'active' => true,
            ],
            [
                'value' => '!=',
                'label' => 'Not equal to',
                'active' => false,
            ],
        ],
        'metadata' => [],
    ]);

    expect($f2->toArray())->toEqual([
        'name' => 'description',
        'label' => 'Description',
        'type' => 'filter:operator',
        'active' => false,
        'value' => null,
        'operators' => [
            [
                'value' => '=',
                'label' => 'Equal to',
                'active' => false,
            ],
            [
                'value' => '!=',
                'label' => 'Not equal to',
                'active' => false,
            ],
        ],
        'metadata' => [],
    ]);
});

it('can apply multiple operator filters', function () {
    $f = OperatorFilter::make('id', 'lower')->operators([Operator::LESS_THAN, Operator::GREATER_THAN]);
    $f2 = OperatorFilter::make('id', 'upper')->operators([Operator::LESS_THAN, Operator::GREATER_THAN]);
    Request::merge(['lower' => 5, '[lower]' => '>', 'upper' => 10, '[upper]' => '<']);
    $builder = Product::query();
    $f->apply($builder);
    $f2->apply($builder);

    expect($f->isActive())->toBeTrue();
    expect($f2->isActive())->toBeTrue();
    expect($builder->toSql())->toBe('select * from "products" where "id" > ? and "id" < ?');

    expect($f->toArray())->toEqual([
        'name' => 'lower',
        'label' => 'Lower',
        'type' => 'filter:operator',
        'active' => true,
        'value' => 5,
        'operators' => [
            [
                'value' => '<',
                'label' => 'Less than',
                'active' => false,
            ],
            [
                'value' => '>',
                'label' => 'Greater than',
                'active' => true,
            ],
        ],
        'metadata' => [],
    ]);

    expect($f2->toArray())->toEqual([
        'name' => 'upper',
        'label' => 'Upper',
        'type' => 'filter:operator',
        'active' => true,
        'value' => 10,
        'operators' => [
            [
                'value' => '<',
                'label' => 'Less than',
                'active' => true,
            ],
            [
                'value' => '>',
                'label' => 'Greater than',
                'active' => false,
            ],
        ],
        'metadata' => [],
    ]);
});
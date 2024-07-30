<?php

use Carbon\Carbon;
use Conquest\Core\Options\Option;
use Workbench\App\Models\Product;
use Illuminate\Support\Facades\DB;
use Conquest\Table\Filters\SetFilter;
use Illuminate\Support\Facades\Request;
use Conquest\Table\Filters\Enums\Operator;
use Conquest\Table\Filters\Enums\Clause;

it('can create a set filter', function () {
    $filter = new SetFilter($n = 'name');
    expect($filter)->getProperty()->toBe($n)
        ->getName()->toBe($n)
        ->getLabel()->toBe('Name')
        ->isAuthorised()->toBeTrue()
        ->canValidate()->toBeFalse()
        ->canTransform()->toBeFalse()
        ->getClause()->toBe(Clause::IS)
        ->getOperator()->toBe(Operator::EQUAL)
        ->isMultiple()->toBeFalse()
        ->hasMeta()->toBeFalse()
        ->hasOptions()->toBeFalse()
        ->isRestricted()->toBeFalse()
        ->getType()->toBe('filter:set');
});

it('can create a set filter with arguments', function () {
    $filter = new SetFilter(
        property: 'name', 
        name: 'username',
        label: 'Name',
        authorize: fn () => false,
        validator: fn () => true,
        transform: fn ($value) => $value,
        clause: Clause::DOES_NOT_CONTAIN,
        operator: Operator::LESS_THAN,
        multiple: true,
        options: [Option::make('value', 'Label')],
        restrict: true,
        meta: ['key' => 'value'],
    );

    expect($filter)->getProperty()->toBe('name')
        ->getName()->toBe('username')
        ->getLabel()->toBe('Name')
        ->isAuthorised()->toBeFalse()
        ->canValidate()->toBeTrue()
        ->canTransform()->toBeTrue()
        ->getClause()->toBe(Clause::DOES_NOT_CONTAIN)
        ->getOperator()->toBe(Operator::LESS_THAN)
        ->isMultiple()->toBeTrue()
        ->hasOptions()->toBeTrue()
        ->isRestricted()->toBeTrue()
        ->getMeta()->toBe(['key' => 'value']);
});

it('can make a set filter', function () {
    expect(SetFilter::make($n = 'name'))->toBeInstanceOf(SetFilter::class)
        ->getProperty()->toBe($n)
        ->getName()->toBe($n)
        ->getLabel()->toBe('Name')
        ->isAuthorised()->toBeTrue()
        ->canValidate()->toBeFalse()
        ->canTransform()->toBeFalse()
        ->getClause()->toBe(Clause::IS)
        ->getOperator()->toBe(Operator::EQUAL)
        ->isMultiple()->toBeFalse()
        ->hasMeta()->toBeFalse()
        ->hasOptions()->toBeFalse()
        ->isRestricted()->toBeFalse()
        ->getType()->toBe('filter:set');
});

it('can chain methods on a set filter', function () {
    $filter = SetFilter::make('name')
        ->name('username')
        ->label('Name')
        ->authorize(fn () => false)
        ->validator(fn () => true)
        ->transform(fn ($value) => $value)
        ->lte()
        ->multiple()
        ->restrict()
        ->meta(['key' => 'value']);

    expect($filter)->toBeInstanceOf(SetFilter::class)
        ->getProperty()->toBe('name')
        ->getName()->toBe('username')
        ->getLabel()->toBe('Name')
        ->isAuthorised()->toBeFalse()
        ->canValidate()->toBeTrue()
        ->canTransform()->toBeTrue()
        ->getClause()->toBe(Clause::CONTAINS)
        ->getOperator()->toBe(Operator::LESS_THAN_OR_EQUAL)
        ->isMultiple()->toBeTrue()
        ->getMeta()->toBe(['key' => 'value']);
});

it('can apply a base set filter to an eloquent builder', function () {
    $filter = SetFilter::make('name');
    $builder = Product::query();
    Request::merge(['name' => 'test']);
    $filter->apply($builder);
    expect($builder->toSql())->toBe('select * from "products" where "name" = ?');
    expect($filter->isActive())->toBeTrue();
});

it('can apply a base set filter to a query builder', function () {
    $filter = SetFilter::make('name');
    $builder = DB::table('products');
    Request::merge(['name' => 'test']);
    $filter->apply($builder);
    expect($builder->toSql())->toBe('select * from "products" where "name" = ?');
    expect($filter->isActive())->toBeTrue();

});

it('does not apply a set filter if name not provided', function () {
    $filter = SetFilter::make('name');
    $builder = Product::query();
    $filter->apply($builder);
    expect($builder->toSql())->toBe('select * from "products"');
    expect($filter->isActive())->toBeFalse();
});

it('does not apply a date filter if name not equal', function () {
    $filter = SetFilter::make('name', 'username');
    $builder = Product::query();
    Request::merge(['name' => '2000-01-01']);
    $filter->apply($builder);
    expect($builder->toSql())->toBe('select * from "products"');
    expect($filter->isActive())->toBeFalse();
});

it('can have options', function () {
    $filter = SetFilter::make('name', 'username')
        ->options([
            Option::make('value', 'Label'),
            Option::make('value2', 'Label2'),
        ]);
    
    expect($filter)->hasOptions()->toBeTrue()
        ->getOptions()->toBeArray()
        ->getOptions()->toHaveCount(2)
        ->getOptions()->each->toBeInstanceOf(Option::class);
});

it('splits values if multiple from request', function () {
    $filter = SetFilter::make('name')->multiple();
    Request::merge(['name' => 'test,test2']);
    expect($filter->getValueFromRequest())->toBeArray()->toHaveCount(2)->toEqual(['test', 'test2']);
    Request::merge(['name' => 'test']);
    expect($filter->getValueFromRequest())->toEqual(['test']);
});

it('does not split if not multiple', function () {
    $filter = SetFilter::make('name');
    Request::merge(['name' => 'test,test2']);
    expect($filter->getValueFromRequest())->toBe('test,test2');
    Request::merge(['name' => 'test']);
    expect($filter->getValueFromRequest())->toBe('test');
});

it('restricts only to provided options', function () {
    $filter = SetFilter::make('name', 'username')
        ->options([
            Option::make('value', 'Label'),
            Option::make('value2', 'Label2'),
        ])
        ->restrict();
    Request::merge(['username' => 'value']);
    $builder = Product::query();
    $filter->apply($builder);
    expect($builder->toSql())->toBe('select * from "products" where "name" = ?');
    expect($filter->isActive())->toBeTrue();
    expect(collect($filter->getOptions())->first())->isActive()->toBeTrue();
});

it('prevents not provided options from being applied', function () {
    $filter = SetFilter::make('name', 'username')
        ->options([
            Option::make('value', 'Label'),
            Option::make('value2', 'Label2'),
        ])
        ->restrict();
    Request::merge(['username' => 'value_']);
    $builder = Product::query();
    $filter->apply($builder);
    expect($builder->toSql())->toBe('select * from "products"');
    expect($filter->isActive())->toBeTrue();
    expect(collect($filter->getOptions())->first())->isActive()->toBeFalse();
});

it('has array representation', function () {
    $filter = SetFilter::make('name');
    expect($filter->toArray())->toEqual([
        'name' => 'name',
        'label' => 'Name',
        'type' => 'filter:set',
        'active' => false,
        'value' => null,
        'meta' => [],
        'options' => [],
        'multiple' => false,
    ]);
});

it('changes array representation if set filter applied', function () {
    $f = SetFilter::make('name');
    $f2 = SetFilter::make('description');
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
        'type' => 'filter:set',
        'active' => true,
        'value' => 'test',
        'meta' => [],
        'options' => [],
        'multiple' => false,
    ]);

    expect($f2->toArray())->toEqual([
        'name' => 'description',
        'label' => 'Description',
        'type' => 'filter:set',
        'active' => false,
        'value' => null,
        'meta' => [],
        'options' => [],
        'multiple' => false,
    ]);
});


it('changes array representation if set filter applied and multiple', function () {
    $f = SetFilter::make('name')->multiple();
    $f2 = SetFilter::make('description');
    Request::merge(['name' => 'test']);
    $builder = Product::query();
    $f->apply($builder);
    $f2->apply($builder);
    expect($f->isActive())->toBeTrue();
    expect($f2->isActive())->toBeFalse();
    expect($builder->toSql())->toBe('select * from "products" where "name" in (?)');

    expect($f->toArray())->toEqual([
        'name' => 'name',
        'label' => 'Name',
        'type' => 'filter:set',
        'active' => true,
        'value' => [
            'test'
        ],
        'meta' => [],
        'options' => [],
        'multiple' => true,
    ]);

    expect($f2->toArray())->toEqual([
        'name' => 'description',
        'label' => 'Description',
        'type' => 'filter:set',
        'active' => false,
        'value' => null,
        'meta' => [],
        'options' => [],
        'multiple' => false,
    ]);
});

<?php

use Carbon\Carbon;
use Workbench\App\Models\Product;
use Illuminate\Support\Facades\DB;
use Conquest\Table\Filters\DateFilter;
use Illuminate\Support\Facades\Request;
use Conquest\Table\Filters\Enums\Operator;
use Conquest\Table\Filters\Enums\DateClause;

it('can create a date filter', function () {
    $filter = new DateFilter($n = 'name');
    expect($filter)->getProperty()->toBe($n)
        ->getName()->toBe($n)
        ->getLabel()->toBe('Name')
        ->isAuthorised()->toBeTrue()
        ->canValidate()->toBeFalse()
        ->canTransform()->toBeFalse()
        ->getClause()->toBe(DateClause::DATE)
        ->getOperator()->toBe(Operator::EQUAL)
        ->hasMetadata()->toBeFalse();
});

it('can create a date filter with arguments', function () {
    $filter = new DateFilter('name', 
        name: 'username',
        authorize: fn () => false,
        validator: fn () => true,
        transform: fn ($value) => $value,
        dateClause: DateClause::MONTH,
        operator: Operator::GREATER_THAN,
        metadata: ['key' => 'value'],
    );

    expect($filter)->getProperty()->toBe('name')
        ->getName()->toBe('username')
        ->getLabel()->toBe('Username')
        ->isAuthorised()->toBeFalse()
        ->canValidate()->toBeTrue()
        ->canTransform()->toBeTrue()
        ->getClause()->toBe(DateClause::MONTH)
        ->getOperator()->toBe(Operator::GREATER_THAN)
        ->getMetadata()->toBe(['key' => 'value']);
});

it('can make a date filter', function () {
    expect(DateFilter::make($n = 'name'))->toBeInstanceOf(DateFilter::class)
        ->getProperty()->toBe($n)
        ->getName()->toBe($n)
        ->getLabel()->toBe('Name')
        ->isAuthorised()->toBeTrue()
        ->canValidate()->toBeFalse()
        ->canTransform()->toBeFalse()
        ->getClause()->toBe(DateClause::DATE)
        ->getOperator()->toBe(Operator::EQUAL)
        ->hasMetadata()->toBeFalse();
});

it('can chain methods on a date filter', function () {
    $filter = DateFilter::make('name')
        ->name('username')
        ->authorize(fn () => false)
        ->validator(fn () => true)
        ->transform(fn ($value) => $value)
        ->month()
        ->gt()
        ->metadata(['key' => 'value']);

    expect($filter)->toBeInstanceOf(DateFilter::class)
        ->getProperty()->toBe('name')
        ->getName()->toBe('username')
        ->getLabel()->toBe('Name')
        ->isAuthorised()->toBeFalse()
        ->canValidate()->toBeTrue()
        ->canTransform()->toBeTrue()
        ->getClause()->toBe(DateClause::MONTH)
        ->getOperator()->toBe(Operator::GREATER_THAN)
        ->getMetadata()->toBe(['key' => 'value']);
});

it('can apply a base date filter to an eloquent builder', function () {
    $filter = DateFilter::make('created_at', 'is');
    $builder = Product::query();
    Request::merge(['is' => '2000-01-01']);
    $filter->apply($builder);
    expect($builder->toSql())->toBe('select * from "products" where strftime(\'%Y-%m-%d\', "created_at") = cast(? as text)');
    expect($filter->isActive())->toBeTrue();
});

it('can apply a base date filter to a query builder', function () {
    $filter = DateFilter::make('created_at', 'is');
    $builder = DB::table('products');
    Request::merge(['is' => '2000-01-01']);
    $filter->apply($builder);
    expect($builder->toSql())->toBe('select * from "products" where strftime(\'%Y-%m-%d\', "created_at") = cast(? as text)');
    expect($filter->isActive())->toBeTrue();
});

it('does not apply a date filter if name not provided', function () {
    $filter = DateFilter::make('created_at', 'is');
    $builder = Product::query();
    $filter->apply($builder);
    expect($builder->toSql())->toBe('select * from "products"');
    expect($filter->isActive())->toBeFalse();
});


it('does not apply a date filter if name not equal', function () {
    $filter = DateFilter::make('created_at', 'is');
    $builder = Product::query();
    Request::merge(['created_at' => '2000-01-01']);
    $filter->apply($builder);
    expect($builder->toSql())->toBe('select * from "products"');
    expect($filter->isActive())->toBeFalse();
});

it('has array representation', function () {
    $filter = DateFilter::make('name');
    expect($filter->toArray())->toEqual([
        'name' => 'name',
        'label' => 'Name',
        'type' => 'filter:date',
        'active' => false,
        'value' => null,
        'metadata' => [],
    ]);
});

it('changes array representation if date filter applied', function () {
    $f = DateFilter::make('created_at', 'created');
    $f2 = DateFilter::make('updated_at', 'updated');
    Request::merge(['created' => $d = '2000-01-01']);
    $builder = Product::query();
    $f->apply($builder);
    $f2->apply($builder);

    expect($f->isActive())->toBeTrue();
    expect($f2->isActive())->toBeFalse();
    expect($builder->toSql())->toBe('select * from "products" where strftime(\'%Y-%m-%d\', "created_at") = cast(? as text)');

    expect($f->toArray())->toEqual([
        'name' => 'created',
        'label' => 'Created',
        'type' => 'filter:date',
        'active' => true,
        'value' => Carbon::parse($d)->toDateTimeString(),
        'metadata' => [],
    ]);

    expect($f2->toArray())->toEqual([
        'name' => 'updated',
        'label' => 'Updated',
        'type' => 'filter:date',
        'active' => false,
        'value' => null,
        'metadata' => [],
    ]);
});

it('can use date clause', function () {
    $filter = DateFilter::make('created_at', 'is')->date();
    $builder = Product::query();
    Request::merge(['is' => '2000-01-01']);
    $filter->apply($builder);
    expect($builder->toSql())->toBe('select * from "products" where strftime(\'%Y-%m-%d\', "created_at") = cast(? as text)');
    expect($filter->isActive())->toBeTrue();
});

it('can use year date clause', function () {
    $filter = DateFilter::make('created_at', 'is')->year();
    $builder = Product::query();
    Request::merge(['is' => '2000-01-01']);
    $filter->apply($builder);
    expect($builder->toSql())->toBe('select * from "products" where strftime(\'%Y\', "created_at") = cast(? as text)');
    expect($filter->isActive())->toBeTrue();
});

it('can use month date clause', function () {
    $filter = DateFilter::make('created_at', 'is')->month();
    $builder = Product::query();
    Request::merge(['is' => '2000-01-01']);
    $filter->apply($builder);
    expect($builder->toSql())->toBe('select * from "products" where strftime(\'%m\', "created_at") = cast(? as text)');
    expect($filter->isActive())->toBeTrue();
});

it('can use day date clause', function () {
    $filter = DateFilter::make('created_at', 'is')->day();
    $builder = Product::query();
    Request::merge(['is' => '2000-01-01']);
    $filter->apply($builder);
    expect($builder->toSql())->toBe('select * from "products" where strftime(\'%d\', "created_at") = cast(? as text)');
    expect($filter->isActive())->toBeTrue();
});

it('can use time date clause', function () {
    $filter = DateFilter::make('created_at', 'is')->time();
    $builder = Product::query();
    Request::merge(['is' => '13:00:00']);
    $filter->apply($builder);
    expect($builder->toSql())->toBe('select * from "products" where strftime(\'%H:%M:%S\', "created_at") = cast(? as text)');
    expect($filter->isActive())->toBeTrue();
});

it('can change the operator', function () {
    $filter = DateFilter::make('created_at', 'is')->gt();
    $builder = Product::query();
    Request::merge(['is' => '2000-01-01']);
    $filter->apply($builder);
    expect($builder->toSql())->toBe('select * from "products" where strftime(\'%Y-%m-%d\', "created_at") > cast(? as text)');
    expect($filter->isActive())->toBeTrue();
});
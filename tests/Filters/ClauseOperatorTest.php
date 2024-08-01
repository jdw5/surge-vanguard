<?php

use Conquest\Table\Filters\DateFilter;
use Conquest\Table\Filters\Enums\Operator;
use Illuminate\Support\Facades\Request;
use Workbench\App\Models\Product;

// it('can use date clause', function () {
//     $filter = DateFilter::make('created_at', 'is')->date();
//     $builder = Product::query();
//     Request::merge(['is' => '2000-01-01']);
//     $filter->apply($builder);
//     expect($builder->toSql())->toBe('select * from "products" where strftime(\'%Y-%m-%d\', "created_at") = cast(? as text)');
//     expect($filter->isActive())->toBeTrue();
// });

// it('can use year date clause', function () {
//     $filter = DateFilter::make('created_at', 'is')->year();
//     $builder = Product::query();
//     Request::merge(['is' => '2000-01-01']);
//     $filter->apply($builder);
//     expect($builder->toSql())->toBe('select * from "products" where strftime(\'%Y\', "created_at") = cast(? as text)');
//     expect($filter->isActive())->toBeTrue();
// });

// it('can use month date clause', function () {
//     $filter = DateFilter::make('created_at', 'is')->month();
//     $builder = Product::query();
//     Request::merge(['is' => '2000-01-01']);
//     $filter->apply($builder);
//     expect($builder->toSql())->toBe('select * from "products" where strftime(\'%m\', "created_at") = cast(? as text)');
//     expect($filter->isActive())->toBeTrue();
// });

// it('can use day date clause', function () {
//     $filter = DateFilter::make('created_at', 'is')->day();
//     $builder = Product::query();
//     Request::merge(['is' => '2000-01-01']);
//     $filter->apply($builder);
//     expect($builder->toSql())->toBe('select * from "products" where strftime(\'%d\', "created_at") = cast(? as text)');
//     expect($filter->isActive())->toBeTrue();
// });

// it('can use time date clause', function () {
//     $filter = DateFilter::make('created_at', 'is')->time();
//     $builder = Product::query();
//     Request::merge(['is' => '13:00:00']);
//     $filter->apply($builder);
//     expect($builder->toSql())->toBe('select * from "products" where strftime(\'%H:%M:%S\', "created_at") = cast(? as text)');
//     expect($filter->isActive())->toBeTrue();
// });

// it('can change the operator', function () {
//     $filter = DateFilter::make('created_at', 'is')->gt();
//     $builder = Product::query();
//     Request::merge(['is' => '2000-01-01']);
//     $filter->apply($builder);
//     expect($builder->toSql())->toBe('select * from "products" where strftime(\'%Y-%m-%d\', "created_at") > cast(? as text)');
//     expect($filter->isActive())->toBeTrue();
// });

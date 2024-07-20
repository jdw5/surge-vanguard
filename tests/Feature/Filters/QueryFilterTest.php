<?php

// use Workbench\App\Models\Product;
// use Illuminate\Support\Facades\DB;
// use Conquest\Table\Filters\QueryFilter;
// use Illuminate\Support\Facades\Request;
// use Conquest\Table\Filters\Enums\Clause;
// use Conquest\Table\Filters\Enums\Operator;
// use Conquest\Table\Filters\Exceptions\QueryNotDefined;

// it('can create a query filter', function () {
//     $filter = new QueryFilter($n = 'name');
//     expect($filter)->getProperty()->toBe($n)
//         ->getName()->toBe($n)
//         ->getLabel()->toBe('Name')
//         ->hasValidator()->toBeFalse()
//         ->isAuthorised()->toBeTrue()
//         ->hasTransform()->toBeFalse()
//         ->hasMetadata()->toBeFalse()
//         ->getQuery()->throw(QueryNotDefined::class);
// });

// it('can create a filter with arguments', function () {
//     $filter = new QueryFilter(
//         name: 'name',
//         label: 'Username',
//         authorize: fn () => false,
//         validator: fn () => true,
//         transform: fn ($value) => $value,
//         query: fn ($builder, $value) => $builder->where('name', $value),
//         metadata: ['key' => 'value'],
//     );

//     expect($filter)->getName()->toBe('name')
//         ->getLabel()->toBe('Username')
//         ->isAuthorised()->toBeFalse()
//         ->hasValidator()->toBeTrue()
//         ->canTransform()->toBeTrue()
//         ->hasMetadata()->toBeTrue()
//         ->getQuery()->notToThrow(QueryNotDefined::class);
// });

// it('can make a filter', function () {
//     expect(QueryFilter::make('name'))->toBeInstanceOf(QueryFilter::class)
//         ->getName()->toBe('name')
//         ->getLabel()->toBe('Name')
//         ->hasValidator()->toBeFalse()
//         ->isAuthorised()->toBeTrue()
//         ->hasTransform()->toBeFalse()
//         ->hasMetadata()->toBeFalse()
//         ->getQuery()->throw(QueryNotDefined::class);
// });

// it('can chain methods on a filter', function () {
//     $q = QueryFilter::make(
//         name: 'name',
//         label: 'Username',
//         authorize: fn () => false,
//         validator: fn () => true,
//         transform: fn ($value) => $value,
//         query: fn ($builder, $value) => $builder->where('name', $value),
//         metadata: ['key' => 'value'],
//     );
//     expect($q)->getName()->toBe('name')
//         ->getLabel()->toBe('Username')
//         ->isAuthorised()->toBeFalse()
//         ->hasValidator()->toBeTrue()
//         ->canTransform()->toBeTrue()
//         ->hasMetadata()->toBeTrue()
//         ->getQuery()->toBeInstanceOf(Closure::class);
// });

// it('throws error when trying to apply query filter without a query', function () {
//     $filter = QueryFilter::make('name');
//     $builder = Product::query();
//     Request::merge(['name' => 'test']);
//     expect($filter->apply($builder))->toThrow(QueryNotDefined::class);
// });
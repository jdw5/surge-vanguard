<?php

use Conquest\Table\Exceptions\CannotResolveNameFromProperty;
use Conquest\Table\Filters\Enums\Clause;
use Conquest\Table\Filters\Enums\Operator;
use Conquest\Table\Filters\Filter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Workbench\App\Models\Product;

it('can instantiate a filter', function () {
    $filter = new Filter('name');
    expect($filter)->toBeInstanceOf(Filter::class)
        ->getProperty()->toBe('name')
        ->getName()->toBe('name')
        ->getLabel()->toBe('Name')
        ->getType()->toBeNull()
        ->getValue()->toBeNull()
        ->hasMeta()->toBeFalse()
        ->isActive()->toBeFalse()
        ->isAuthorized()->toBeTrue()
        ->canTransform()->toBeFalse()
        ->getClause()->toBe(Clause::Is)
        ->getOperator()->toBe(Operator::Equal);
});

it('throws error if array of properties given and no name', function () {
    $f = Filter::make(['a', 'b']);
})->throws(CannotResolveNameFromProperty::class);

it('can instantiate a filter using resolvable property', function () {
    expect(Filter::make(fn () => 'name'))->toBeInstanceOf(Filter::class)
        ->getProperty()->toBe('name')
        ->getName()->toBe('name')
        ->getLabel()->toBe('Name')
        ->getType()->toBeNull()
        ->getValue()->toBeNull()
        ->hasMeta()->toBeFalse()
        ->isActive()->toBeFalse()
        ->isAuthorized()->toBeTrue()
        ->canTransform()->toBeFalse()
        ->getClause()->toBe(Clause::Is)
        ->getOperator()->toBe(Operator::Equal);
});

describe('base make', function () {
    beforeEach(function () {
        $this->filter = Filter::make('name');
    });

    it('retrieves value from request', function () {
        Request::merge(['name' => 'John']);
        expect($this->filter->getValueFromRequest())->toBe('John');
    });

    it('retrieves null from request', function () {
        expect($this->filter->getValueFromRequest())->toBeNull();
    });

    it('retrieves null from request if incorrect name', function () {
        Request::merge(['not' => 'John']);
        expect($this->filter->getValueFromRequest())->toBeNull();
    });

    it('can filter', function () {
        expect($this->filter->filtering('John'))->toBeTrue();
        expect($this->filter->filtering(null))->toBeFalse();
    });

    it('can make a filter', function () {
        expect($this->filter)->toBeInstanceOf(Filter::class)
            ->getProperty()->toBe('name')
            ->getName()->toBe('name')
            ->getLabel()->toBe('Name')
            ->getType()->toBeNull()
            ->getValue()->toBeNull()
            ->hasMeta()->toBeFalse()
            ->isActive()->toBeFalse()
            ->isAuthorized()->toBeTrue()
            ->canTransform()->toBeFalse()
            ->getClause()->toBe(Clause::Is)
            ->getOperator()->toBe(Operator::Equal);
    });

    it('has array form', function () {
        expect($this->filter->toArray())->toEqual([
            'name' => 'name',
            'label' => 'Name',
            'type' => null,
            'active' => false,
            'value' => null,
            'meta' => [],
        ]);
    });
});

describe('chain make', function () {
    beforeEach(function () {
        $this->filter = Filter::make('name')
            ->name('person')
            ->label('Username')
            ->authorize(fn () => false)
            ->transform(fn ($value) => mb_strtoupper($value))
            ->meta(['key' => 'value'])
            ->isNot()
            ->neq();
    });

    it('can chain methods on a filter', function () {
        expect($this->filter)->toBeInstanceOf(Filter::class)
            ->getProperty()->toBe('name')
            ->getName()->toBe('person')
            ->getLabel()->toBe('Username')
            ->getType()->toBeNull()
            ->getValue()->toBeNull()
            ->hasMeta()->toBeTrue()
            ->getMeta()->toBe(['key' => 'value'])
            ->isActive()->toBeFalse()
            ->isAuthorized()->toBeFalse()
            ->canTransform()->toBeTrue()
            ->getClause()->toBe(Clause::IsNot)
            ->getOperator()->toBe(Operator::NotEqual);
    });

    it('has array form', function () {
        expect($this->filter->toArray())->toEqual([
            'name' => 'person',
            'label' => 'Username',
            'type' => null,
            'active' => false,
            'value' => null,
            'meta' => ['key' => 'value'],
        ]);
    });
});

describe('can be applied', function () {
    describe('to Eloquent builder', function () {
        beforeEach(function () {
            $this->filter = Filter::make('name');
            $this->builder = Product::query();
        });

        it('can be handled', function () {
            $this->filter->handle($this->builder);
            expect($this->builder->toSql())->toBe('select * from "products" where "name" is null');
        });

        it('can be handled with value', function () {
            $this->filter->value('John')->clause(Clause::IsNot)->operator(Operator::Like);
            $this->filter->handle($this->builder);
            expect($this->builder->toSql())->toBe('select * from "products" where not "name" like ?');
        });

        describe('without request', function () {
            beforeEach(function () {
                $this->filter = Filter::make('name');
                $this->builder = Product::query();
            });

            it('is not applied to builder', function () {
                $this->filter->apply($this->builder);
                expect($this->builder->toSql())->toBe('select * from "products"');
                expect($this->filter->getValue())->toBeNull();
                expect($this->filter->isActive())->toBeFalse();
            });
        });

        describe('with request', function () {
            beforeEach(function () {
                $this->filter = Filter::make('name');
                $this->builder = Product::query();
                Request::merge(['name' => 'John']);
            });

            it('can apply to builder', function () {
                $this->filter->apply($this->builder);
                expect($this->builder->toSql())->toBe('select * from "products" where "name" = ?');
                expect($this->filter->getValue())->toBe('John');
                expect($this->filter->isActive())->toBeTrue();
            });

            it('can apply and transforms before setting value', function () {
                $this->filter->transform(fn ($value) => mb_strtoupper($value));
                $this->filter->apply($this->builder);
                expect($this->builder->toSql())->toBe('select * from "products" where "name" = ?');
                expect($this->filter->getValue())->toBe('JOHN');
                expect($this->filter->isActive())->toBeTrue();
            });

            it('can apply and transforms and validates before handling', function () {
                $this->filter->validate(fn ($value) => ! is_null($value) && strlen($value) > 4);
                $this->filter->apply($this->builder);
                expect($this->builder->toSql())->toBe('select * from "products"');
                expect($this->filter->getValue())->toBe('John');
                expect($this->filter->isActive())->toBeTrue();
            });
        });
    });

    describe('to Query builder', function () {
        beforeEach(function () {
            $this->filter = Filter::make('name');
            $this->builder = DB::table('products');
        });

        it('can be handled', function () {
            $this->filter->handle($this->builder);
            expect($this->builder->toSql())->toBe('select * from "products" where "name" is null');
        });

        it('can be handled with value', function () {
            $this->filter->value('John')->clause(Clause::IsNot)->Operator(Operator::Like);
            $this->filter->handle($this->builder);
            expect($this->builder->toSql())->toBe('select * from "products" where not "name" like ?');
        });

        describe('no request', function () {
            beforeEach(function () {
                $this->filter = Filter::make('name');
                $this->builder = DB::table('products');
            });

            it('is not applied to builder', function () {
                $this->filter->apply($this->builder);
                expect($this->builder->toSql())->toBe('select * from "products"');
                expect($this->filter->getValue())->toBeNull();
                expect($this->filter->isActive())->toBeFalse();
            });
        });

        describe('request', function () {
            beforeEach(function () {
                $this->filter = Filter::make('name');
                $this->builder = DB::table('products');
                Request::merge(['name' => 'John']);
            });

            it('can apply to builder', function () {
                $this->filter->apply($this->builder);
                expect($this->builder->toSql())->toBe('select * from "products" where "name" = ?');
                expect($this->filter->getValue())->toBe('John');
                expect($this->filter->isActive())->toBeTrue();
            });

            it('can apply and transforms before setting value', function () {
                $this->filter->transform(fn ($value) => mb_strtoupper($value));
                $this->filter->apply($this->builder);
                expect($this->builder->toSql())->toBe('select * from "products" where "name" = ?');
                expect($this->filter->getValue())->toBe('JOHN');
                expect($this->filter->isActive())->toBeTrue();
            });

            it('can apply and transforms and validates before handling', function () {
                $this->filter->validate(fn ($value) => ! is_null($value) && strlen($value) > 4);
                $this->filter->apply($this->builder);
                expect($this->builder->toSql())->toBe('select * from "products"');
                expect($this->filter->getValue())->toBe('John');
                expect($this->filter->isActive())->toBeTrue();
            });
        });
    });
});

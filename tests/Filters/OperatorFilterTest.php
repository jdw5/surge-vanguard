<?php

use Conquest\Table\Exceptions\CannotResolveNameFromProperty;
use Conquest\Table\Filters\Enums\Clause;
use Conquest\Table\Filters\Enums\Operator;
use Conquest\Table\Filters\OperatorFilter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Workbench\App\Models\Product;

it('can instantiate an operator filter', function () {
    $filter = new OperatorFilter('name');
    expect($filter)->toBeInstanceOf(OperatorFilter::class)
        ->getProperty()->toBe('name')
        ->getName()->toBe('name')
        ->getLabel()->toBe('Name')
        ->getType()->toBe('operator')
        ->getValue()->toBeNull()
        ->hasMeta()->toBeFalse()
        ->isActive()->toBeFalse()
        ->isAuthorized()->toBeTrue()
        ->canTransform()->toBeFalse()
        ->getClause()->toBe(Clause::Is)
        ->getOperator()->toBeNull()
        ->hasOperators()->toBeFalse()
        ->getOperators()->toBe([]);
});

it('throws error if array of properties given and no name', function () {
    $f = OperatorFilter::make(['a', 'b']);
})->throws(CannotResolveNameFromProperty::class);

it('can instantiate an operator filter using resolvable property', function () {
    expect(OperatorFilter::make(fn () => 'name'))->toBeInstanceOf(OperatorFilter::class)
        ->getProperty()->toBe('name')
        ->getName()->toBe('name')
        ->getLabel()->toBe('Name')
        ->getType()->toBe('operator')
        ->getValue()->toBeNull()
        ->hasMeta()->toBeFalse()
        ->isActive()->toBeFalse()
        ->isAuthorized()->toBeTrue()
        ->canTransform()->toBeFalse()
        ->getClause()->toBe(Clause::Is)
        ->getOperator()->toBeNull()
        ->hasOperators()->toBeFalse()
        ->getOperators()->toBe([]);
});

describe('base make', function () {
    beforeEach(function () {
        $this->filter = OperatorFilter::make('name');
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

    it('retrieves operator from request', function () {
        Request::merge(['[name]' => '>']);
        expect($this->filter->getOperatorFromRequest())->toBe(Operator::GreaterThan);
    });

    it('retrieves null operator from request', function () {
        expect($this->filter->getOperatorFromRequest())->toBeNull();
    });

    it('retrieves null from request if incorrect name for operator', function () {
        Request::merge(['[not]' => '=']);
        expect($this->filter->getOperatorFromRequest())->toBeNull();
    });

    it('retrieves null from request if invalid operator', function () {
        Request::merge(['[name]' => '==']);
        expect($this->filter->getOperatorFromRequest())->toBeNull();
    });

    it('can filter', function () {
        expect($this->filter->filtering('John'))->toBeFalse();
        expect($this->filter->filtering(null))->toBeFalse();
        $this->filter->operators(Operator::Equal)->operator(Operator::Equal);
        expect($this->filter->filtering('John'))->toBeTrue();
        $this->filter->operator(Operator::NotEqual);
        expect($this->filter->filtering('John'))->toBeFalse();
    });

    it('can make an operator filter', function () {
        expect($this->filter)->toBeInstanceOf(OperatorFilter::class)
            ->getProperty()->toBe('name')
            ->getName()->toBe('name')
            ->getLabel()->toBe('Name')
            ->getType()->toBe('operator')
            ->getValue()->toBeNull()
            ->hasMeta()->toBeFalse()
            ->isActive()->toBeFalse()
            ->isAuthorized()->toBeTrue()
            ->canTransform()->toBeFalse()
            ->getClause()->toBe(Clause::Is)
            ->getOperator()->toBeNull()
            ->getOperators()->toBe([]);
    });

    it('has array form', function () {
        expect($this->filter->toArray())->toEqual([
            'name' => 'name',
            'label' => 'Name',
            'type' => 'operator',
            'active' => false,
            'value' => null,
            'meta' => [],
            'operators' => [],
        ]);
    });
});

describe('chain make', function () {
    beforeEach(function () {
        $this->filter = OperatorFilter::make('name')
            ->name('person')
            ->label('Username')
            ->authorize(fn () => false)
            ->transform(fn ($value) => mb_strtoupper($value))
            ->meta(['key' => 'value'])
            ->isNot()
            ->neq()
            ->operators(Operator::Equal, Operator::NotEqual);
    });

    it('can chain methods on an operator filter', function () {
        expect($this->filter)->toBeInstanceOf(OperatorFilter::class)
            ->getProperty()->toBe('name')
            ->getName()->toBe('person')
            ->getLabel()->toBe('Username')
            ->getType()->toBe('operator')
            ->getValue()->toBeNull()
            ->hasMeta()->toBeTrue()
            ->getMeta()->toBe(['key' => 'value'])
            ->isActive()->toBeFalse()
            ->isAuthorized()->toBeFalse()
            ->canTransform()->toBeTrue()
            ->getClause()->toBe(Clause::IsNot)
            ->getOperator()->toBe(Operator::NotEqual)
            ->getOperators()->toEqual([Operator::Equal, Operator::NotEqual]);
    });

    it('has array form', function () {
        expect($this->filter->toArray())->toEqual([
            'name' => 'person',
            'label' => 'Username',
            'type' => 'operator',
            'active' => false,
            'value' => null,
            'meta' => ['key' => 'value'],
            'operators' => $this->filter->getOperatorOptions(Operator::NotEqual->value)->toArray(),
        ]);
    });
});

describe('can be applied', function () {
    describe('to Eloquent builder', function () {
        beforeEach(function () {
            $this->filter = OperatorFilter::make('name')->operator(Operator::Equal);
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
                $this->filter = OperatorFilter::make('name')->operators(Operator::Equal, Operator::NotEqual);
                $this->builder = Product::query();
            });

            it('is not applied to builder', function () {
                $this->filter->apply($this->builder);
                expect($this->builder->toSql())->toBe('select * from "products"');
                expect($this->filter->getValue())->toBeNull();
                expect($this->filter->isActive())->toBeFalse();
            });

            it('is not applied to builder with only name', function () {
                Request::merge(['name' => 'john']);
                $this->filter->apply($this->builder);
                expect($this->builder->toSql())->toBe('select * from "products"');
                expect($this->filter->getValue())->toBe('john');
                expect($this->filter->isActive())->toBeFalse();
                expect($this->filter->hasOperator())->toBeFalse();
            });

            it('is not applied to builder with only operator', function () {
                Request::merge(['[name]' => '>']);
                $this->filter->apply($this->builder);
                expect($this->builder->toSql())->toBe('select * from "products"');
                expect($this->filter->getValue())->toBeNull();
                expect($this->filter->isActive())->toBeFalse();
                expect($this->filter->hasOperator())->toBeTrue();
            });
        });

        describe('with request', function () {
            beforeEach(function () {
                $this->filter = OperatorFilter::make('name')->operators(Operator::Equal, Operator::NotEqual);
                $this->builder = Product::query();
                Request::merge(['name' => 'John', '[name]' => '=']);
            });

            it('can apply to builder', function () {
                $this->filter->apply($this->builder);
                expect($this->builder->toSql())->toBe('select * from "products" where "name" = ?');
                expect($this->filter->getValue())->toBe('John');
                expect($this->filter->isActive())->toBeTrue();
                expect($this->filter->hasOperator())->toBeTrue();
            });

            it('can apply and transforms before setting value', function () {
                $this->filter->transform(fn ($value) => mb_strtoupper($value));
                $this->filter->apply($this->builder);
                expect($this->builder->toSql())->toBe('select * from "products" where "name" = ?');
                expect($this->filter->getValue())->toBe('JOHN');
                expect($this->filter->isActive())->toBeTrue();
                expect($this->filter->hasOperator())->toBeTrue();
            });

            it('can apply and transforms and validates before handling', function () {
                $this->filter->validate(fn ($value) => ! is_null($value) && strlen($value) > 4);
                $this->filter->apply($this->builder);
                expect($this->builder->toSql())->toBe('select * from "products"');
                expect($this->filter->getValue())->toBe('John');
                expect($this->filter->isActive())->toBeTrue();
                expect($this->filter->hasOperator())->toBeTrue();
            });

            it('validates the operator', function () {
                Request::merge(['name' => 'John', '[name]' => '>']);
                $this->filter->apply($this->builder);
                expect($this->builder->toSql())->toBe('select * from "products"');
                expect($this->filter->getValue())->toBe('John');
                expect($this->filter->hasOperator())->toBeTrue();
                expect($this->filter->isActive())->toBeFalse();

            });
        });
    });

    describe('to Query builder', function () {
        beforeEach(function () {
            $this->filter = OperatorFilter::make('name')->operator(Operator::Equal);
            $this->builder = DB::table('products');
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

        describe('no request', function () {
            beforeEach(function () {
                $this->filter = OperatorFilter::make('name')->operators(Operator::Equal, Operator::NotEqual);
                $this->builder = DB::table('products');
            });

            it('is not applied to builder', function () {
                $this->filter->apply($this->builder);
                expect($this->builder->toSql())->toBe('select * from "products"');
                expect($this->filter->getValue())->toBeNull();
                expect($this->filter->isActive())->toBeFalse();
            });

            it('is not applied to builder with only name', function () {
                Request::merge(['name' => 'john']);
                $this->filter->apply($this->builder);
                expect($this->builder->toSql())->toBe('select * from "products"');
                expect($this->filter->getValue())->toBe('john');
                expect($this->filter->isActive())->toBeFalse();
                expect($this->filter->hasOperator())->toBeFalse();
            });

            it('is not applied to builder with only operator', function () {
                Request::merge(['[name]' => '>']);
                $this->filter->apply($this->builder);
                expect($this->builder->toSql())->toBe('select * from "products"');
                expect($this->filter->getValue())->toBeNull();
                expect($this->filter->isActive())->toBeFalse();
                expect($this->filter->hasOperator())->toBeTrue();
            });
        });

        describe('request', function () {
            beforeEach(function () {
                $this->filter = OperatorFilter::make('name')->operators(Operator::Equal, Operator::NotEqual);
                $this->builder = DB::table('products');
                Request::merge(['name' => 'John', '[name]' => '=']);
            });

            it('can apply to builder', function () {
                $this->filter->apply($this->builder);
                expect($this->builder->toSql())->toBe('select * from "products" where "name" = ?');
                expect($this->filter->getValue())->toBe('John');
                expect($this->filter->isActive())->toBeTrue();
                expect($this->filter->hasOperator())->toBeTrue();
            });

            it('can apply and transforms before setting value', function () {
                $this->filter->transform(fn ($value) => mb_strtoupper($value));
                $this->filter->apply($this->builder);
                expect($this->builder->toSql())->toBe('select * from "products" where "name" = ?');
                expect($this->filter->getValue())->toBe('JOHN');
                expect($this->filter->isActive())->toBeTrue();
                expect($this->filter->hasOperator())->toBeTrue();
            });

            it('can apply and transforms and validates before handling', function () {
                $this->filter->validate(fn ($value) => ! is_null($value) && strlen($value) > 4);
                $this->filter->apply($this->builder);
                expect($this->builder->toSql())->toBe('select * from "products"');
                expect($this->filter->getValue())->toBe('John');
                expect($this->filter->isActive())->toBeTrue();
                expect($this->filter->hasOperator())->toBeTrue();
            });

            it('validates the operator', function () {
                Request::merge(['name' => 'John', '[name]' => '>']);
                $this->filter->apply($this->builder);
                expect($this->builder->toSql())->toBe('select * from "products"');
                expect($this->filter->getValue())->toBe('John');
                expect($this->filter->hasOperator())->toBeTrue();
                expect($this->filter->isActive())->toBeFalse();

            });
        });
    });

});

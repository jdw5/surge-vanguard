<?php

use Conquest\Table\Exceptions\CannotResolveNameFromProperty;
use Conquest\Table\Filters\Enums\Clause;
use Conquest\Table\Filters\Enums\Operator;
use Conquest\Table\Filters\SetFilter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Workbench\App\Models\Product;

it('can instantiate a set filter', function () {
    $filter = new SetFilter('name');
    expect($filter)->toBeInstanceOf(SetFilter::class)
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
        ->getOperator()->toBe(Operator::Equal)
        ->isRestricted()->toBeFalse()
        ->isMultiple()->toBeFalse()
        ->hasOptions()->toBeFalse()
        ->getOptions()->toEqual([]);
});

it('throws error if array of properties given and no name', function () {
    $f = SetFilter::make(['a', 'b']);
})->throws(CannotResolveNameFromProperty::class);

it('can instantiate a set filter using resolvable property', function () {
    expect(SetFilter::make(fn () => 'name'))->toBeInstanceOf(SetFilter::class)
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
        ->getOperator()->toBe(Operator::Equal)
        ->isRestricted()->toBeFalse()
        ->isMultiple()->toBeFalse()
        ->hasOptions()->toBeFalse()
        ->getOptions()->toEqual([]);
});

describe('base make', function () {
    beforeEach(function () {
        $this->filter = SetFilter::make('name');
    });

    it('overrides set multiple to add contains clause', function () {
        $this->filter->setMultiple(true);
        expect($this->filter->isMultiple())->toBeTrue();
        expect($this->filter->getClause())->toBe(Clause::Contains);
    });

    it('uses the clause and attribute to determine multiplicity', function () {
        $this->filter->multiple()->clause(Clause::Is);
        expect($this->filter->isMultiple())->toBeFalse();
        expect($this->filter->getClause())->toBe(Clause::Is);
    });

    it('retrieves value from request for single', function () {
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

    it('retrieves array from request if multiple', function () {
        Request::merge(['name' => 'John']);
        expect($this->filter->getValueFromRequest())->toEqual(['John']);
    });

    it('retrieves array from request if multiple using comma separation', function () {
        Request::merge(['name' => 'John,Jane']);
        expect($this->filter->getValueFromRequest())->toEqual(['John', 'Jane']);
    });

    it('can filter', function () {
        expect($this->filter->filtering('John'))->toBeFalse();
        expect($this->filter->filtering(null))->toBeFalse();
    });

    it('can make a set filter', function () {
        expect($this->filter)->toBeInstanceOf(SetFilter::class)
            ->getProperty()->toBe('name')
            ->getName()->toBe('name')
            ->getLabel()->toBe('Name')
            ->getType()->toBe('set')
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
            'type' => 'set',
            'active' => false,
            'value' => null,
            'meta' => [],
        ]);
    });
});

describe('chain make', function () {
    beforeEach(function () {
        $this->filter = SetFilter::make('name')
            ->name('person')
            ->label('Username')
            ->authorize(fn () => false)
            ->transform(fn ($value) => mb_strtoupper($value))
            ->meta(['key' => 'value'])
            ->isNot()
            ->neq()
            ->options(['John', 'Jane', 'Jack'])
            ->restricted()
            ->multiple();
    });

    it('can chain methods on a set filter', function () {
        expect($this->filter)->toBeInstanceOf(SetFilter::class)
            ->getProperty()->toBe('name')
            ->getName()->toBe('person')
            ->getLabel()->toBe('Username')
            ->getType()->toBe('set')
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
            'type' => 'set',
            'active' => false,
            'value' => null,
            'meta' => ['key' => 'value'],
        ]);
    });
});

describe('can be applied', function () {
    describe('to Eloquent builder', function () {
        beforeEach(function () {
            $this->filter = SetFilter::make('name');
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
                $this->filter = SetFilter::make('name');
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
                $this->filter = SetFilter::make('name')->operators(Operator::Equal, Operator::NotEqual);
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
            $this->filter = SetFilter::make('name')->operator(Operator::Equal);
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
                $this->filter = SetFilter::make('name')->operators(Operator::Equal, Operator::NotEqual);
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
                $this->filter = SetFilter::make('name')->operators(Operator::Equal, Operator::NotEqual);
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

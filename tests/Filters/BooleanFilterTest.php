<?php

use Conquest\Table\Exceptions\CannotResolveNameFromProperty;
use Conquest\Table\Filters\BooleanFilter;
use Conquest\Table\Filters\Enums\Clause;
use Conquest\Table\Filters\Enums\Operator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Workbench\App\Models\Product;

it('can instantiate a boolean filter', function () {
    $filter = new BooleanFilter('name');
    expect($filter)->toBeInstanceOf(BooleanFilter::class)
        ->getProperty()->toBe('name')
        ->getName()->toBe('name')
        ->getLabel()->toBe('Name')
        ->getValue()->toBeNull()
        ->getType()->toBe('boolean')
        ->hasMeta()->toBeFalse()
        ->isActive()->toBeFalse()
        ->isAuthorized()->toBeTrue()
        ->canTransform()->toBeFalse()
        ->getClause()->toBe(Clause::Is)
        ->getOperator()->toBe(Operator::Equal);
});

it('throws error if array of properties given and no name', function () {
    $f = BooleanFilter::make(['a', 'b']);
})->throws(CannotResolveNameFromProperty::class);

it('can instantiate a boolean filter using resolvable property', function () {
    expect(BooleanFilter::make(fn () => 'name'))->toBeInstanceOf(BooleanFilter::class)
        ->getProperty()->toBe('name')
        ->getName()->toBe('name')
        ->getLabel()->toBe('Name')
        ->getType()->toBe('boolean')
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
        $this->filter = BooleanFilter::make('name')->value('john');
    });

    it('retrieves value from request 1', function () {
        Request::merge(['name' => 1]);
        expect($this->filter->getValueFromRequest())->toBeTrue();
    });

    it('retrieves value from request true', function () {
        Request::merge(['name' => true]);
        expect($this->filter->getValueFromRequest())->toBeTrue();
    });

    it('retrieves value from request true string', function () {
        Request::merge(['name' => 'true']);
        expect($this->filter->getValueFromRequest())->toBeTrue();
    });

    it('retrieves value from request 0', function () {
        Request::merge(['name' => 0]);
        expect($this->filter->getValueFromRequest())->toBeFalse();
    });

    it('retrieves value from request false', function () {
        Request::merge(['name' => 'false']);
        expect($this->filter->getValueFromRequest())->toBeFalse();
    });

    it('retrieves null from request', function () {
        expect($this->filter->getValueFromRequest())->toBeFalse();
    });

    it('retrieves null from request if incorrect name', function () {
        Request::merge(['not' => 'John']);
        expect($this->filter->getValueFromRequest())->toBeFalse();
    });

    it('can filter', function () {
        expect($this->filter->filtering('John'))->toBeTrue();
        expect($this->filter->filtering(null))->toBeFalse();
    });

    it('can make a filter', function () {
        expect($this->filter)->toBeInstanceOf(BooleanFilter::class)
            ->getProperty()->toBe('name')
            ->getName()->toBe('name')
            ->getLabel()->toBe('Name')
            ->getType()->toBe('boolean')
            ->getValue()->toBe('john')
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
            'type' => 'boolean',
            'active' => false,
            'meta' => [],
        ]);
    });
});

describe('chain make', function () {
    beforeEach(function () {
        $this->filter = BooleanFilter::make('name')
            ->name('person')
            ->label('Username')
            ->authorize(fn () => false)
            ->meta(['key' => 'value'])
            ->value(5)
            ->isNot()
            ->neq();
    });

    it('can chain methods on a filter', function () {
        expect($this->filter)->toBeInstanceOf(BooleanFilter::class)
            ->getProperty()->toBe('name')
            ->getName()->toBe('person')
            ->getLabel()->toBe('Username')
            ->getType()->toBe('boolean')
            ->getValue()->toBe(5)
            ->hasMeta()->toBeTrue()
            ->getMeta()->toBe(['key' => 'value'])
            ->isActive()->toBeFalse()
            ->isAuthorized()->toBeFalse()
            ->getClause()->toBe(Clause::IsNot)
            ->getOperator()->toBe(Operator::NotEqual);
    });

    it('has array form', function () {
        expect($this->filter->toArray())->toEqual([
            'name' => 'person',
            'label' => 'Username',
            'type' => 'boolean',
            'active' => false,
            'meta' => ['key' => 'value'],
        ]);
    });
});

describe('can be applied', function () {
    describe('to Eloquent builder', function () {
        beforeEach(function () {
            $this->filter = BooleanFilter::make('name')->value('john');
            $this->builder = Product::query();
        });

        it('can be handled', function () {
            $this->filter->handle($this->builder);
            expect($this->builder->toSql())->toBe('select * from "products" where "name" = ?');
        });

        it('can be handled with value', function () {
            $this->filter->clause(Clause::IsNot)->Operator(Operator::Like);
            $this->filter->handle($this->builder);
            expect($this->builder->toSql())->toBe('select * from "products" where not "name" like ?');
        });

        describe('without request', function () {
            beforeEach(function () {
                $this->filter = BooleanFilter::make('name')->value('john');
                $this->builder = Product::query();
            });

            it('is not applied to builder', function () {
                $this->filter->apply($this->builder);
                expect($this->builder->toSql())->toBe('select * from "products"');
                expect($this->filter->getValue())->toBe('john');
                expect($this->filter->isActive())->toBeFalse();
            });
        });

        describe('with request', function () {
            beforeEach(function () {
                $this->filter = BooleanFilter::make('name')->value('john');
                $this->builder = Product::query();
                Request::merge(['name' => 'true']);
            });

            it('can apply to builder', function () {
                $this->filter->apply($this->builder);
                expect($this->builder->toSql())->toBe('select * from "products" where "name" = ?');
                expect($this->filter->getValue())->toBe('john');
                expect($this->filter->isActive())->toBeTrue();
            });

            it('requires a boolean value', function () {
                Request::merge(['name' => 'john']);
                $this->filter->apply($this->builder);
                expect($this->builder->toSql())->toBe('select * from "products"');
                expect($this->filter->getValue())->toBe('john');
                expect($this->filter->isActive())->toBeFalse();
            });
        });
    });

    describe('to Query builder', function () {
        beforeEach(function () {
            $this->filter = BooleanFilter::make('name')->value('john');
            $this->builder = DB::table('products');
        });

        it('can be handled', function () {
            $this->filter->handle($this->builder);
            expect($this->builder->toSql())->toBe('select * from "products" where "name" = ?');
        });

        it('can be handled with value', function () {
            $this->filter->clause(Clause::IsNot)->Operator(Operator::Like);
            $this->filter->handle($this->builder);
            expect($this->builder->toSql())->toBe('select * from "products" where not "name" like ?');
        });

        describe('no request', function () {
            beforeEach(function () {
                $this->filter = BooleanFilter::make('name')->value('john');
                $this->builder = DB::table('products');
            });

            it('is not applied to builder', function () {
                $this->filter->apply($this->builder);
                expect($this->builder->toSql())->toBe('select * from "products"');
                expect($this->filter->getValue())->toBe('john');
                expect($this->filter->isActive())->toBeFalse();
            });
        });

        describe('request', function () {
            beforeEach(function () {
                $this->filter = BooleanFilter::make('name')->value('john');
                $this->builder = DB::table('products');
                Request::merge(['name' => 1]);
            });

            it('can apply to builder', function () {
                $this->filter->apply($this->builder);
                expect($this->builder->toSql())->toBe('select * from "products" where "name" = ?');
                expect($this->filter->getValue())->toBe('john');
                expect($this->filter->isActive())->toBeTrue();
            });
        });
    });

});

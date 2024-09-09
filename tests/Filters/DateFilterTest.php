<?php

use Carbon\Carbon;
use Conquest\Table\Exceptions\CannotResolveNameFromProperty;
use Conquest\Table\Filters\DateFilter;
use Conquest\Table\Filters\Enums\DateClause;
use Conquest\Table\Filters\Enums\Operator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Workbench\App\Models\Product;

it('can instantiate a filter', function () {
    $filter = new DateFilter('created_at');
    expect($filter)->toBeInstanceOf(DateFilter::class)
        ->getProperty()->toBe('created_at')
        ->getName()->toBe('created_at')
        ->getLabel()->toBe('Created at')
        ->getType()->toBe('date')
        ->getValue()->toBeNull()
        ->hasMeta()->toBeFalse()
        ->isActive()->toBeFalse()
        ->isAuthorized()->toBeTrue()
        ->canTransform()->toBeFalse()
        ->getDateClause()->toBe(DateClause::Date)
        ->getOperator()->toBe(Operator::Equal);
});

it('throws error if array of properties given and no name', function () {
    $f = DateFilter::make(['a', 'b']);
})->throws(CannotResolveNameFromProperty::class);

it('can instantiate a filter using resolvable property', function () {
    expect(DateFilter::make(fn () => 'created_at'))->toBeInstanceOf(DateFilter::class)
        ->getProperty()->toBe('created_at')
        ->getName()->toBe('created_at')
        ->getLabel()->toBe('Created at')
        ->getType()->toBe('date')
        ->getValue()->toBeNull()
        ->hasMeta()->toBeFalse()
        ->isActive()->toBeFalse()
        ->isAuthorized()->toBeTrue()
        ->canTransform()->toBeFalse()
        ->getDateClause()->toBe(DateClause::Date)
        ->getOperator()->toBe(Operator::Equal);
});

describe('base make', function () {
    beforeEach(function () {
        $this->filter = DateFilter::make('created_at');
    });

    it('retrieves value from request', function () {
        Request::merge(['created_at' => '1990-01-01']);
        expect($this->filter->getValueFromRequest())->toEqual(Carbon::parse('1990-01-01'));
    });

    it('retrieves null from request if invalid date', function () {
        Request::merge(['created_at' => 'word']);
        expect($this->filter->getValueFromRequest())->toBeNull();
    });

    it('retrieves null from request', function () {
        expect($this->filter->getValueFromRequest())->toBeNull();
    });

    it('retrieves null from request if incorrect name', function () {
        Request::merge(['not' => '1990-01-01']);
        expect($this->filter->getValueFromRequest())->toBeNull();
    });

    it('can filter', function () {
        expect($this->filter->filtering(now()))->toBeTrue();
        expect($this->filter->filtering(null))->toBeFalse();
    });

    it('can make a filter', function () {
        expect($this->filter)->toBeInstanceOf(DateFilter::class)
            ->getProperty()->toBe('created_at')
            ->getName()->toBe('created_at')
            ->getLabel()->toBe('Created at')
            ->getType()->toBe('date')
            ->getValue()->toBeNull()
            ->hasMeta()->toBeFalse()
            ->isActive()->toBeFalse()
            ->isAuthorized()->toBeTrue()
            ->canTransform()->toBeFalse()
            ->getDateClause()->toBe(DateClause::Date)
            ->getOperator()->toBe(Operator::Equal);
    });

    it('has array form', function () {
        expect($this->filter->toArray())->toEqual([
            'name' => 'created_at',
            'label' => 'Created at',
            'type' => 'date',
            'active' => false,
            'value' => null,
            'meta' => [],
        ]);
    });

    it('has array form with date time string', function () {
        $this->filter->value(Carbon::parse('1990-01-01'));
        expect($this->filter->toArray())->toEqual([
            'name' => 'created_at',
            'label' => 'Created at',
            'type' => 'date',
            'active' => false,
            'value' => '1990-01-01 00:00:00',
            'meta' => [],
        ]);
    });
});

describe('chain make', function () {
    beforeEach(function () {
        $this->filter = DateFilter::make('created_at')
            ->name('when')
            ->label('Made at')
            ->authorize(fn () => false)
            ->transform(fn ($value) => mb_strtoupper($value))
            ->meta(['key' => 'value'])
            ->year()
            ->neq();
    });

    it('can chain methods on a filter', function () {
        expect($this->filter)->toBeInstanceOf(DateFilter::class)
            ->getProperty()->toBe('created_at')
            ->getName()->toBe('when')
            ->getLabel()->toBe('Made at')
            ->getType()->toBe('date')
            ->getValue()->toBeNull()
            ->hasMeta()->toBeTrue()
            ->getMeta()->toBe(['key' => 'value'])
            ->isActive()->toBeFalse()
            ->isAuthorized()->toBeFalse()
            ->canTransform()->toBeTrue()
            ->getDateClause()->toBe(DateClause::Year)
            ->getOperator()->toBe(Operator::NotEqual);
    });

    it('has array form', function () {
        expect($this->filter->toArray())->toEqual([
            'name' => 'when',
            'label' => 'Made at',
            'type' => 'date',
            'active' => false,
            'value' => null,
            'meta' => ['key' => 'value'],
        ]);
    });
});

describe('can be applied', function () {
    describe('to Eloquent builder', function () {
        beforeEach(function () {
            $this->filter = DateFilter::make('created_at');
            $this->builder = Product::query();
        });

        it('can be handled', function () {
            $this->filter->value(Carbon::parse('01-01-2000'));
            $this->filter->handle($this->builder);
            expect($this->builder->toSql())->toBe('select * from "products" where strftime(\'%Y-%m-%d\', "created_at") = cast(? as text)');
        });

        describe('without request', function () {
            beforeEach(function () {
                $this->filter = DateFilter::make('created_at');
                $this->builder = Product::query();
            });

            it('is not applied to builder', function () {
                $this->filter->apply($this->builder);
                expect($this->builder->toSql())->toBe('select * from "products"');
                expect($this->filter->getValue())->toBeNull();
                expect($this->filter->isActive())->toBeFalse();
            });
        });

        describe('with valid request', function () {
            beforeEach(function () {
                $this->filter = DateFilter::make('created_at');
                $this->builder = Product::query();
                Request::merge(['created_at' => '01-01-2000']);
            });

            it('can apply to builder', function () {
                $this->filter->apply($this->builder);
                expect($this->builder->toSql())->toBe('select * from "products" where strftime(\'%Y-%m-%d\', "created_at") = cast(? as text)');
                expect($this->filter->getValue())->toEqual(Carbon::parse('01-01-2000'));
                expect($this->filter->isActive())->toBeTrue();
            });

            it('can apply and transforms before setting value', function () {
                $this->filter->transform(fn ($value) => $value instanceof Carbon ? $value->addDay() : $value);
                $this->filter->apply($this->builder);
                expect($this->builder->toSql())->toBe('select * from "products" where strftime(\'%Y-%m-%d\', "created_at") = cast(? as text)');
                expect($this->filter->getValue())->toEqual(Carbon::parse('01-01-2000')->addDay());
                expect($this->filter->isActive())->toBeTrue();
            });

            it('can apply and validats before handling', function () {
                $this->filter->validate(fn ($value) => $value instanceof Carbon && $value->year > 2001);
                $this->filter->apply($this->builder);
                expect($this->builder->toSql())->toBe('select * from "products"');
                expect($this->filter->getValue())->toEqual(Carbon::parse('01-01-2000'));
                expect($this->filter->isActive())->toBeTrue();
            });
        });
    });

    describe('to Query builder', function () {
        beforeEach(function () {
            $this->filter = DateFilter::make('created_at');
            $this->builder = DB::table('products');
        });

        it('can be handled', function () {
            $this->filter->value(Carbon::parse('01-01-2000'));
            $this->filter->handle($this->builder);
            expect($this->builder->toSql())->toBe('select * from "products" where strftime(\'%Y-%m-%d\', "created_at") = cast(? as text)');
        });

        describe('no request', function () {
            beforeEach(function () {
                $this->filter = DateFilter::make('created_at');
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
                $this->filter = DateFilter::make('created_at');
                $this->builder = DB::table('products');
                Request::merge(['created_at' => '01-01-2000']);
            });

            it('can apply to builder', function () {
                $this->filter->apply($this->builder);
                expect($this->builder->toSql())->toBe('select * from "products" where strftime(\'%Y-%m-%d\', "created_at") = cast(? as text)');
                expect($this->filter->getValue())->toEqual(Carbon::parse('01-01-2000'));
                expect($this->filter->isActive())->toBeTrue();
            });

            it('can apply and transforms before setting value', function () {
                $this->filter->transform(fn ($value) => $value instanceof Carbon ? $value->addDay() : $value);
                $this->filter->apply($this->builder);
                expect($this->builder->toSql())->toBe('select * from "products" where strftime(\'%Y-%m-%d\', "created_at") = cast(? as text)');
                expect($this->filter->getValue())->toEqual(Carbon::parse('01-01-2000')->addDay());
                expect($this->filter->isActive())->toBeTrue();
            });

            it('can apply and validats before handling', function () {
                $this->filter->validate(fn ($value) => $value instanceof Carbon && $value->year > 2001);
                $this->filter->apply($this->builder);
                expect($this->builder->toSql())->toBe('select * from "products"');
                expect($this->filter->getValue())->toEqual(Carbon::parse('01-01-2000'));
                expect($this->filter->isActive())->toBeTrue();
            });
        });
    });
});

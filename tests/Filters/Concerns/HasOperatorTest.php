<?php

use Conquest\Table\Filters\Enums\Operator;
use Conquest\Table\Filters\Filter;

beforeEach(function () {
    $this->filter = Filter::make('name');
});

it('has "equals" operator by default', function () {
    expect($this->filter->getOperator())->toBe(Operator::Equal);
    expect($this->filter->hasOperator())->toBeTrue();
    expect($this->filter->lacksOperator())->toBeFalse();
});

it('can set an operator', function () {
    $this->filter->setOperator(Operator::NotEqual);
    expect($this->filter->getOperator())->toBe(Operator::NotEqual);
    expect($this->filter->hasOperator())->toBeTrue();
    expect($this->filter->lacksOperator())->toBeFalse();
});

it('can set an operator via string', function () {
    $this->filter->setOperator('>');
    expect($this->filter->getOperator())->toBe(Operator::GreaterThan);
    expect($this->filter->hasOperator())->toBeTrue();
    expect($this->filter->lacksOperator())->toBeFalse();
});

it('throws error if invalid string operator', function () {
    $this->filter->setOperator('!!');
})->throws(ValueError::class);

it('can set an operator through chaining', function () {
    expect($this->filter->operator(Operator::NotEqual))->toBeInstanceOf(Filter::class)
        ->getOperator()->toBe(Operator::NotEqual)
        ->hasOperator()->toBeTrue()
        ->lacksOperator()->toBeFalse();
});

it('prevents null behaviour from being set', function () {
    $this->filter->setOperator(null);
    expect($this->filter)
        ->getOperator()->toBe(Operator::Equal)
        ->hasOperator()->toBeTrue()
        ->lacksOperator()->toBeFalse();
});

it('can set "equal" operator through shorthand chain', function () {
    expect($this->filter->eq())->toBeInstanceOf(Filter::class)
        ->getOperator()->toBe(Operator::Equal);
});

it('has alias for "equal" operator "equal"', function () {
    expect($this->filter->equal())->toBeInstanceOf(Filter::class)
        ->getOperator()->toBe(Operator::Equal);
});

it('has alias for "equal" operator "equals"', function () {
    expect($this->filter->equals())->toBeInstanceOf(Filter::class)
        ->getOperator()->toBe(Operator::Equal);
});

it('can set "not equal" operator through shorthand chain', function () {
    expect($this->filter->neq())->toBeInstanceOf(Filter::class)
        ->getOperator()->toBe(Operator::NotEqual);
});

it('has alias for "not equal" operator "notEqual"', function () {
    expect($this->filter->notEqual())->toBeInstanceOf(Filter::class)
        ->getOperator()->toBe(Operator::NotEqual);
});

it('can set "greater than" operator through shorthand chain', function () {
    expect($this->filter->gt())->toBeInstanceOf(Filter::class)
        ->getOperator()->toBe(Operator::GreaterThan);
});

it('has alias for "greater than" operator "greaterThan"', function () {
    expect($this->filter->greaterThan())->toBeInstanceOf(Filter::class)
        ->getOperator()->toBe(Operator::GreaterThan);
});

it('has alias for "greater than" operator "greater"', function () {
    expect($this->filter->greater())->toBeInstanceOf(Filter::class)
        ->getOperator()->toBe(Operator::GreaterThan);
});

it('can set "greater than or equal" operator through shorthand chain', function () {
    expect($this->filter->gte())->toBeInstanceOf(Filter::class)
        ->getOperator()->toBe(Operator::GreaterThanOrEqual);
});

it('has alias for "greater than or equal" operator "greaterThanOrEqual"', function () {
    expect($this->filter->greaterThanOrEqual())->toBeInstanceOf(Filter::class)
        ->getOperator()->toBe(Operator::GreaterThanOrEqual);
});

it('can set "less than" operator through shorthand chain', function () {
    expect($this->filter->lt())->toBeInstanceOf(Filter::class)
        ->getOperator()->toBe(Operator::LessThan);
});

it('has alias for "less than" operator "lessThan"', function () {
    expect($this->filter->lessThan())->toBeInstanceOf(Filter::class)
        ->getOperator()->toBe(Operator::LessThan);
});

it('has alias for "less than" operator "lesser"', function () {
    expect($this->filter->lesser())->toBeInstanceOf(Filter::class)
        ->getOperator()->toBe(Operator::LessThan);
});

it('can set "less than or equal" operator through shorthand chain', function () {
    expect($this->filter->lte())->toBeInstanceOf(Filter::class)
        ->getOperator()->toBe(Operator::LessThanOrEqual);
});

it('has alias for "less than or equal" operator "lessThanOrEqual"', function () {
    expect($this->filter->lessThanOrEqual())->toBeInstanceOf(Filter::class)
        ->getOperator()->toBe(Operator::LessThanOrEqual);
});

it('can set "like" operator through shorthand chain "fuzzy"', function () {
    expect($this->filter->fuzzy())->toBeInstanceOf(Filter::class)
        ->getOperator()->toBe(Operator::Like);
});

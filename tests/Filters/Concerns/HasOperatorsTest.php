<?php

use Conquest\Table\Filters\Enums\Operator;
use Conquest\Table\Filters\OperatorFilter;

beforeEach(function () {
    $this->filter = OperatorFilter::make('name');
});

it('has no operators by default', function () {
    expect($this->filter->getOperators())->toBeNull();
    expect($this->filter->hasOperators())->toBeFalse();
    expect($this->filter->lacksOperators())->toBeTrue();
});

it('can set operators', function () {
    $this->filter->setOperators(Operator::Equal, Operator::NotEqual);
    expect($this->filter->getOperators())->toEqual([Operator::Equal, Operator::NotEqual]);
    expect($this->filter->hasOperators())->toBeTrue();
    expect($this->filter->lacksOperators())->toBeFalse();
});

it('can set operators through chaining', function () {
    expect($this->filter->operators(Operator::Equal, Operator::NotEqual))->toBeInstanceOf(OperatorFilter::class)
        ->getOperators()->toEqual([Operator::Equal, Operator::NotEqual])
        ->hasOperators()->toBeTrue()
        ->lacksOperators()->toBeFalse();
});

it('prevents empty behaviour from being set', function () {
    $this->filter->setOperators();
    expect($this->filter)
        ->getOperators()->toBeNull()
        ->hasOperators()->toBeFalse()
        ->lacksOperators()->toBeTrue();
});

it('retrieves operators as serialized options', function () {
    $this->filter->setOperators(Operator::Equal, Operator::NotEqual);
    expect($this->filter->getOperatorOptions())->toEqual(collect([
        ['value' => '=', 'label' => 'Equal to', 'active' => false],
        ['value' => '!=', 'label' => 'Not equal to', 'active' => false],
    ]));
});

it('retrieves operators as serialized options with active', function () {
    $this->filter->setOperators(Operator::Equal, Operator::NotEqual);
    expect($this->filter->getOperatorOptions('='))->toEqual(collect([
        ['value' => '=', 'label' => 'Equal to', 'active' => true],
        ['value' => '!=', 'label' => 'Not equal to', 'active' => false],
    ]));
});

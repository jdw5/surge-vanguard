<?php

use Conquest\Table\Filters\SetFilter;

beforeEach(function () {
    $this->action = SetFilter::make('Delete');
});

it('is not multiple by default', function () {
    expect($this->action->isMultiple())->toBeFalse();
    expect($this->action->isNotMultiple())->toBeTrue();
});

it('can set as multiple', function () {
    $this->action->setMultiple(true);
    expect($this->action->isMultiple())->toBeTrue();
    expect($this->action->isNotMultiple())->toBeFalse();
});

it('can set as multiple through resolver', function () {
    $this->action->setMultiple(fn () => true);
    expect($this->action->isMultiple())->toBeTrue();
    expect($this->action->isNotMultiple())->toBeFalse();
});

it('can set as multiple through chaining', function () {
    expect($this->action->multiple())->toBeInstanceOf(SetFilter::class)
        ->isMultiple()->toBeTrue()
        ->isNotMultiple()->toBeFalse();
});

it('prevents null behaviour from being set', function () {
    $this->action->setMultiple(null);
    expect($this->action)
        ->isMultiple()->toBeFalse()
        ->isNotMultiple()->toBeTrue();
});
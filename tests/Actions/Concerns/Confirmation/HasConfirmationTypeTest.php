<?php

use Conquest\Table\Actions\BulkAction;
use Conquest\Table\Actions\Concerns\Confirmation\ConfirmationType;

beforeEach(function () {
    $this->action = BulkAction::make('Delete');
});

it('has no confirmation type by default', function (){
    expect($this->action->getConfirmationType())->toBeNull();
    expect($this->action->hasConfirmationType())->toBeFalse();
    expect($this->action->lacksConfirmationType())->toBeTrue();
});

it('can set a confirmation type string', function () {
    $this->action->setConfirmationType('other');
    expect($this->action->getConfirmationType())->toBe('other');
    expect($this->action->hasConfirmationType())->toBeTrue();
    expect($this->action->lacksConfirmationType())->toBeFalse();
});

it('can set a confirmation type enum', function () {
    $this->action->setConfirmationType(ConfirmationType::Constructive);
    expect($this->action->getConfirmationType())->toBe(ConfirmationType::Constructive->value);
    expect($this->action->hasConfirmationType())->toBeTrue();
    expect($this->action->lacksConfirmationType())->toBeFalse();
});

it('can set a resolvable confirmation type', function () {
    $this->action->setConfirmationType(fn () => 'other');
    expect($this->action->getConfirmationType())->toBe('other');
    expect($this->action->hasConfirmationType())->toBeTrue();
    expect($this->action->lacksConfirmationType())->toBeFalse();
});

it('can set a confirmation type through chaining', function () {
    expect($this->action->confirmationType('other'))->toBeInstanceOf(BulkAction::class)
        ->getConfirmationType()->toBe('other')
        ->hasConfirmationType()->toBeTrue()
        ->lacksConfirmationType()->toBeFalse();
});

it('prevents null behaviour from being set', function () {
    $this->action->setConfirmationType(null);
    expect($this->action)
        ->getConfirmationType()->toBe(config('table.confirmation.type'))
        ->hasConfirmationType()->toBeFalse()
        ->lacksConfirmationType()->toBeTrue();
});

it('can set as constructive', function () {
    expect($this->action->constructive())
        ->getConfirmationType()->toBe(ConfirmationType::Constructive->value)
        ->hasConfirmationType()->toBeTrue()
        ->lacksConfirmationType()->toBeFalse();
});

it('can set as destructive', function () {
    expect($this->action->destructive())
        ->getConfirmationType()->toBe(ConfirmationType::Destructive->value)
        ->hasConfirmationType()->toBeTrue()
        ->lacksConfirmationType()->toBeFalse();
});

it('can set as informative', function () {
    expect($this->action->informative())
        ->getConfirmationType()->toBe(ConfirmationType::Informative->value)
        ->hasConfirmationType()->toBeTrue()
        ->lacksConfirmationType()->toBeFalse();
});
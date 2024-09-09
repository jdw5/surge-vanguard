<?php

use Conquest\Table\Actions\BulkAction;
use Conquest\Table\Actions\Concerns\Confirm\ConfirmType;

beforeEach(function () {
    $this->action = BulkAction::make('Delete');
});

it('has no confirm type by default', function () {
    expect($this->action->getConfirmType())->toBeNull();
    expect($this->action->hasConfirmType())->toBeFalse();
    expect($this->action->lacksConfirmType())->toBeTrue();
});

it('can set a confirm type string', function () {
    $this->action->setConfirmType('other');
    expect($this->action->getConfirmType())->toBe('other');
    expect($this->action->hasConfirmType())->toBeTrue();
    expect($this->action->lacksConfirmType())->toBeFalse();
});

it('can set a confirm type enum', function () {
    $this->action->setConfirmType(ConfirmType::Constructive);
    expect($this->action->getConfirmType())->toBe(ConfirmType::Constructive->value);
    expect($this->action->hasConfirmType())->toBeTrue();
    expect($this->action->lacksConfirmType())->toBeFalse();
});

it('can set a resolvable confirm type', function () {
    $this->action->setConfirmType(fn () => 'other');
    expect($this->action->getConfirmType())->toBe('other');
    expect($this->action->hasConfirmType())->toBeTrue();
    expect($this->action->lacksConfirmType())->toBeFalse();
});

it('can set a confirm type through chaining', function () {
    expect($this->action->confirmType('other'))->toBeInstanceOf(BulkAction::class)
        ->getConfirmType()->toBe('other')
        ->hasConfirmType()->toBeTrue()
        ->lacksConfirmType()->toBeFalse();
});

it('prevents null behaviour from being set', function () {
    $this->action->setConfirmType(null);
    expect($this->action)
        ->getConfirmType()->toBe(config('table.confirm.type'))
        ->hasConfirmType()->toBeFalse()
        ->lacksConfirmType()->toBeTrue();
});

it('can set as constructive', function () {
    expect($this->action->constructive())
        ->getConfirmType()->toBe(ConfirmType::Constructive->value)
        ->hasConfirmType()->toBeTrue()
        ->lacksConfirmType()->toBeFalse();
});

it('can set as destructive', function () {
    expect($this->action->destructive())
        ->getConfirmType()->toBe(ConfirmType::Destructive->value)
        ->hasConfirmType()->toBeTrue()
        ->lacksConfirmType()->toBeFalse();
});

it('can set as informative', function () {
    expect($this->action->informative())
        ->getConfirmType()->toBe(ConfirmType::Informative->value)
        ->hasConfirmType()->toBeTrue()
        ->lacksConfirmType()->toBeFalse();
});

<?php

use Conquest\Table\Actions\BulkAction;

beforeEach(function () {
    $this->action = BulkAction::make('Delete');
});

it('is not confirmable by default', function () {
    expect($this->action->isConfirmable())->toBeFalse();
    expect($this->action->isNotConfirmable())->toBeTrue();
});

it('can set as confirmable', function () {
    $this->action->setConfirmable(true);
    expect($this->action->isConfirmable())->toBeTrue();
    expect($this->action->isNotConfirmable())->toBeFalse();
});

it('can set as confirmable through resolver', function () {
    $this->action->setConfirmable(fn () => true);
    expect($this->action->isConfirmable())->toBeTrue();
    expect($this->action->isNotConfirmable())->toBeFalse();
});

it('can set as confirmable through chaining', function () {
    expect($this->action->confirmable())->toBeInstanceOf(BulkAction::class)
        ->isConfirmable()->toBeTrue()
        ->isNotConfirmable()->toBeFalse();
});

it('prevents null behaviour from being set', function () {
    $this->action->setConfirmable(null);
    expect($this->action)
        ->isConfirmable()->toBeFalse()
        ->isNotConfirmable()->toBeTrue();
});

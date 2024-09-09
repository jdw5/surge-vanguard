<?php

use Conquest\Table\Actions\BulkAction;

beforeEach(function () {
    $this->action = BulkAction::make('Delete');
});

it('uses confirm message default', function () {
    expect($this->action->getConfirmMessage())->toBe(config('table.confirm.message'));
    expect($this->action->hasConfirmMessage())->toBeFalse();
    expect($this->action->lacksConfirmMessage())->toBeTrue();
});

it('can set a confirm message', function () {
    $this->action->setConfirmMessage('Message');
    expect($this->action->getConfirmMessage())->toBe('Message');
    expect($this->action->hasConfirmMessage())->toBeTrue();
    expect($this->action->lacksConfirmMessage())->toBeFalse();
});

it('can set a resolvable confirm message', function () {
    $this->action->setConfirmMessage(fn () => 'Message');
    expect($this->action->getConfirmMessage())->toBe('Message');
    expect($this->action->hasConfirmMessage())->toBeTrue();
    expect($this->action->lacksConfirmMessage())->toBeFalse();
});

it('can set a confirm message through chaining', function () {
    expect($this->action->confirmMessage('Message'))->toBeInstanceOf(BulkAction::class)
        ->getConfirmMessage()->toBe('Message')
        ->hasConfirmMessage()->toBeTrue()
        ->lacksConfirmMessage()->toBeFalse();
});

it('prevents null behaviour from being set', function () {
    $this->action->setConfirmMessage(null);
    expect($this->action)
        ->getConfirmMessage()->toBe(config('table.confirm.message'))
        ->hasConfirmMessage()->toBeFalse()
        ->lacksConfirmMessage()->toBeTrue();
});

<?php

use Conquest\Table\Actions\BulkAction;

beforeEach(function () {
    $this->action = BulkAction::make('Delete');
});

it('uses confirmation message default', function (){
    expect($this->action->getConfirmationMessage())->toBe(config('table.confirmation.message'));
    expect($this->action->hasConfirmationMessage())->toBeFalse();
    expect($this->action->lacksConfirmationMessage())->toBeTrue();
});

it('can set a confirmation message', function () {
    $this->action->setConfirmationMessage('Message');
    expect($this->action->getConfirmationMessage())->toBe('Message');
    expect($this->action->hasConfirmationMessage())->toBeTrue();
    expect($this->action->lacksConfirmationMessage())->toBeFalse();
});

it('can set a resolvable confirmation message', function () {
    $this->action->setConfirmationMessage(fn () => 'Message');
    expect($this->action->getConfirmationMessage())->toBe('Message');
    expect($this->action->hasConfirmationMessage())->toBeTrue();
    expect($this->action->lacksConfirmationMessage())->toBeFalse();
});

it('can set a confirmation message through chaining', function () {
    expect($this->action->confirmationMessage('Message'))->toBeInstanceOf(BulkAction::class)
        ->getConfirmationMessage()->toBe('Message')
        ->hasConfirmationMessage()->toBeTrue()
        ->lacksConfirmationMessage()->toBeFalse();
});

it('prevents null behaviour from being set', function () {
    $this->action->setConfirmationMessage(null);
    expect($this->action)
        ->getConfirmationMessage()->toBe(config('table.confirmation.message'))
        ->hasConfirmationMessage()->toBeFalse()
        ->lacksConfirmationMessage()->toBeTrue();
});
<?php

use Conquest\Table\Actions\BulkAction;

beforeEach(function () {
    $this->action = BulkAction::make('Delete');
});

it('uses confirmation title default', function (){
    expect($this->action->getConfirmationTitle())->toBe(config('table.confirmation.title'));
    expect($this->action->hasConfirmationTitle())->toBeFalse();
    expect($this->action->lacksConfirmationTitle())->toBeTrue();
});

it('can set a confirmation title', function () {
    $this->action->setConfirmationTitle('Title');
    expect($this->action->getConfirmationTitle())->toBe('Title');
    expect($this->action->hasConfirmationTitle())->toBeTrue();
    expect($this->action->lacksConfirmationTitle())->toBeFalse();
});

it('can set a resolvable confirmation title', function () {
    $this->action->setConfirmationTitle(fn () => 'Title');
    expect($this->action->getConfirmationTitle())->toBe('Title');
    expect($this->action->hasConfirmationTitle())->toBeTrue();
    expect($this->action->lacksConfirmationTitle())->toBeFalse();
});

it('can set a confirmation title through chaining', function () {
    expect($this->action->confirmationTitle('Title'))->toBeInstanceOf(BulkAction::class)
        ->getConfirmationTitle()->toBe('Title')
        ->hasConfirmationTitle()->toBeTrue()
        ->lacksConfirmationTitle()->toBeFalse();
});

it('prevents null behaviour from being set', function () {
    $this->action->setConfirmationTitle(null);
    expect($this->action)
        ->getConfirmationTitle()->toBe(config('table.confirmation.title'))
        ->hasConfirmationTitle()->toBeFalse()
        ->lacksConfirmationTitle()->toBeTrue();
});
<?php

use Conquest\Table\Actions\BulkAction;

beforeEach(function () {
    $this->action = BulkAction::make('Delete');
});

it('uses confirm title default', function () {
    expect($this->action->getConfirmTitle())->toBe(config('table.confirm.title'));
    expect($this->action->hasConfirmTitle())->toBeFalse();
    expect($this->action->lacksConfirmTitle())->toBeTrue();
});

it('can set a confirm title', function () {
    $this->action->setConfirmTitle('Title');
    expect($this->action->getConfirmTitle())->toBe('Title');
    expect($this->action->hasConfirmTitle())->toBeTrue();
    expect($this->action->lacksConfirmTitle())->toBeFalse();
});

it('can set a resolvable confirm title', function () {
    $this->action->setConfirmTitle(fn () => 'Title');
    expect($this->action->getConfirmTitle())->toBe('Title');
    expect($this->action->hasConfirmTitle())->toBeTrue();
    expect($this->action->lacksConfirmTitle())->toBeFalse();
});

it('can set a confirm title through chaining', function () {
    expect($this->action->confirmTitle('Title'))->toBeInstanceOf(BulkAction::class)
        ->getConfirmTitle()->toBe('Title')
        ->hasConfirmTitle()->toBeTrue()
        ->lacksConfirmTitle()->toBeFalse();
});

it('prevents null behaviour from being set', function () {
    $this->action->setConfirmTitle(null);
    expect($this->action)
        ->getConfirmTitle()->toBe(config('table.confirm.title'))
        ->hasConfirmTitle()->toBeFalse()
        ->lacksConfirmTitle()->toBeTrue();
});

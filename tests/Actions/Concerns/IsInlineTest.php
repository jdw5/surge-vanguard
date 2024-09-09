<?php

use Conquest\Table\Actions\BulkAction;

beforeEach(function () {
    $this->action = BulkAction::make('Delete');
});

it('is not inline by default', function () {
    expect($this->action->isInline())->toBeFalse();
    expect($this->action->isNotInline())->toBeTrue();
});

it('can set as inline', function () {
    $this->action->setInline(true);
    expect($this->action->isInline())->toBeTrue();
    expect($this->action->isNotInline())->toBeFalse();
});

it('can set as inline through resolver', function () {
    $this->action->setInline(fn () => true);
    expect($this->action->isInline())->toBeTrue();
    expect($this->action->isNotInline())->toBeFalse();
});

it('can set as inline through chaining', function () {
    expect($this->action->inline())->toBeInstanceOf(BulkAction::class)
        ->isInline()->toBeTrue()
        ->isNotInline()->toBeFalse();
});

it('prevents null behaviour from being set', function () {
    $this->action->setInline(null);
    expect($this->action)
        ->isInline()->toBeFalse()
        ->isNotInline()->toBeTrue();
});

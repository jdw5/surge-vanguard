<?php

use Conquest\Table\Actions\BulkAction;

beforeEach(function () {
    $this->action = BulkAction::make('Duplicate');
});

it('is deselectable by default', function () {
    expect($this->action->isDeselectable())->toBeTrue();
    expect($this->action->isNotDeselectable())->toBeFalse();
});

it('can set as deselectable', function () {
    $this->action->setDeselectable(false);
    expect($this->action->isDeselectable())->toBeFalse();
    expect($this->action->isNotDeselectable())->toBeTrue();
});

it('can set as deselectable through resolver', function () {
    $this->action->setDeselectable(fn () => false);
    expect($this->action->isDeselectable())->toBeFalse();
    expect($this->action->isNotDeselectable())->toBeTrue();
});

it('can set as deselectable through chaining', function () {
    expect($this->action->deselect())->toBeInstanceOf(BulkAction::class)
        ->isDeselectable()->toBeFalse()
        ->isNotDeselectable()->toBeTrue();
});

it('prevents null behaviour from being set', function () {
    $this->action->setDeselectable(null);
    expect($this->action)
        ->isDeselectable()->toBeTrue()
        ->isNotDeselectable()->toBeFalse();
});

it('has alias for deselectable', function () {
    expect($this->action->deselect())->toBeInstanceOf(BulkAction::class)
        ->isDeselectable()->toBeFalse()
        ->isNotDeselectable()->toBeTrue();
});

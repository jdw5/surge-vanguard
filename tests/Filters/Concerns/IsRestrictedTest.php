<?php

use Conquest\Table\Filters\SetFilter;

beforeEach(function () {
    $this->action = SetFilter::make('Delete');
});

it('is not restricted by default', function () {
    expect($this->action->isRestricted())->toBeFalse();
    expect($this->action->isNotRestricted())->toBeTrue();
});

it('can set as restricted', function () {
    $this->action->setRestricted(true);
    expect($this->action->isRestricted())->toBeTrue();
    expect($this->action->isNotRestricted())->toBeFalse();
});

it('can set as restricted through resolver', function () {
    $this->action->setRestricted(fn () => true);
    expect($this->action->isRestricted())->toBeTrue();
    expect($this->action->isNotRestricted())->toBeFalse();
});

it('can set as restricted through chaining', function () {
    expect($this->action->restricted())->toBeInstanceOf(SetFilter::class)
        ->isRestricted()->toBeTrue()
        ->isNotRestricted()->toBeFalse();
});

it('can set as unrestricted through chaining', function () {
    expect($this->action->unrestricted())->toBeInstanceOf(SetFilter::class)
        ->isRestricted()->toBeFalse()
        ->isNotRestricted()->toBeTrue();
});

it('prevents null behaviour from being set', function () {
    $this->action->setRestricted(null);
    expect($this->action)
        ->isRestricted()->toBeFalse()
        ->isNotRestricted()->toBeTrue();
});

<?php

use Conquest\Table\Actions\InlineAction;

beforeEach(function () {
    $this->action = InlineAction::make('Delete');
});

it('is not bulk by default', function () {
    expect($this->action->isBulk())->toBeFalse();
    expect($this->action->isNotBulk())->toBeTrue();
});

it('can set as bulk', function () {
    $this->action->setBulk(true);
    expect($this->action->isBulk())->toBeTrue();
    expect($this->action->isNotBulk())->toBeFalse();
});

it('can set as bulk through resolver', function () {
    $this->action->setBulk(fn () => true);
    expect($this->action->isBulk())->toBeTrue();
    expect($this->action->isNotBulk())->toBeFalse();
});

it('can set as bulk through chaining', function () {
    expect($this->action->bulk())->toBeInstanceOf(InlineAction::class)
        ->isBulk()->toBeTrue()
        ->isNotBulk()->toBeFalse();
});

it('prevents null behaviour from being set', function () {
    $this->action->setBulk(null);
    expect($this->action)
        ->isBulk()->toBeFalse()
        ->isNotBulk()->toBeTrue();
});

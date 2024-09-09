<?php

use Conquest\Table\Actions\BulkAction;

beforeEach(function () {
    $this->action = BulkAction::make('Delete');
});

it('uses configuration defaults', function () {
    expect($this->action->getChunkSize())->toBe(config('table.chunk.size'));
    expect($this->action->hasChunkSize())->toBeFalse();
    expect($this->action->lacksChunkSize())->toBeTrue();
});

it('can set a chunkSize', function () {
    $this->action->setChunkSize(1000);
    expect($this->action->getChunkSize())->toBe(1000);
    expect($this->action->hasChunkSize())->toBeTrue();
    expect($this->action->lacksChunkSize())->toBeFalse();
});

it('can set a resolvable chunkSize', function () {
    $this->action->setChunkSize(fn () => 1000);
    expect($this->action->getChunkSize())->toBe(1000);
    expect($this->action->hasChunkSize())->toBeTrue();
    expect($this->action->lacksChunkSize())->toBeFalse();
});

it('can set a chunkSize through chaining', function () {
    expect($this->action->chunkSize(1000))->toBeInstanceOf(BulkAction::class)
        ->getChunkSize()->toBe(1000)
        ->hasChunkSize()->toBeTrue()
        ->lacksChunkSize()->toBeFalse();
});

it('prevents null behaviour from being set', function () {
    $this->action->setChunkSize(null);
    expect($this->action)
        ->getChunkSize()->toBe(config('table.chunk.size'))
        ->hasChunkSize()->toBeFalse()
        ->lacksChunkSize()->toBeTrue();
});

<?php

use Conquest\Table\Actions\BulkAction;

beforeEach(function () {
    $this->action = BulkAction::make('Delete');
});

it('uses configuration defaults', function () {
    expect($this->action->getChunkById())->toBe(config('table.chunk.by_id'));
    expect($this->action->hasChunkById())->toBeFalse();
    expect($this->action->lacksChunkById())->toBeTrue();
});

it('can set a chunk by id', function () {
    $this->action->setChunkById(false);
    expect($this->action->getChunkById())->toBeFalse();
    expect($this->action->hasChunkById())->toBeTrue();
    expect($this->action->lacksChunkById())->toBeFalse();
});

it('can set a resolvable chunk by id', function () {
    $this->action->setChunkById(fn () => false);
    expect($this->action->getChunkById())->toBeFalse();
    expect($this->action->hasChunkById())->toBeTrue();
    expect($this->action->lacksChunkById())->toBeFalse();
});

it('can set a chunk by id through chaining', function () {
    expect($this->action->chunkById(false))->toBeInstanceOf(BulkAction::class)
        ->getChunkById()->toBeFalse()
        ->hasChunkById()->toBeTrue()
        ->lacksChunkById()->toBeFalse();
});

it('prevents null behaviour from being set', function () {
    $this->action->setChunkById(null);
    expect($this->action)
        ->getChunkById()->toBe(config('table.chunk.by_id'))
        ->hasChunkById()->toBeFalse()
        ->lacksChunkById()->toBeTrue();
});

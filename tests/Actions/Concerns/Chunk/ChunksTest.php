<?php

use Conquest\Table\Actions\BulkAction;

beforeEach(function () {
    $this->action = BulkAction::make('Delete');
});

it('uses configuration defaults', function () {
    expect($this->action->getChunkSize())->toBe(config('table.chunk.size'));
    expect($this->action->hasChunkSize())->toBeFalse();
    expect($this->action->lacksChunkSize())->toBeTrue();
    expect($this->action->getChunkById())->toBe(config('table.chunk.by_id'));
    expect($this->action->hasChunkById())->toBeFalse();
    expect($this->action->lacksChunkById())->toBeTrue();
});

it('can set chunk through chaining', function () {
    expect($this->action->chunk(1000, false))->toBeInstanceOf(BulkAction::class)
        ->getChunkById()->toBeFalse()
        ->hasChunkById()->toBeTrue()
        ->lacksChunkById()->toBeFalse()
        ->getChunkSize()->toBe(1000)
        ->hasChunkSize()->toBeTrue()
        ->lacksChunkSize()->toBeFalse();
});

it('gets the chunk method', function () {
    expect($this->action->getChunkMethod())->toBe('chunkById');
    $this->action->chunk(1000, false);
    expect($this->action->getChunkMethod())->toBe('chunk');
});

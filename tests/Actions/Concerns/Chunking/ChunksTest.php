<?php

use Conquest\Table\Actions\BulkAction;

beforeEach(function () {
    $this->action = BulkAction::make('Delete');
});

it('uses configuration defaults', function (){
    expect($this->action->getChunkSize())->toBe(config('table.chunking.size'));
    expect($this->action->hasChunkSize())->toBeFalse();
    expect($this->action->lacksChunkSize())->toBeTrue();
});
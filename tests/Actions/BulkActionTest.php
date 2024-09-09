<?php

use Conquest\Table\Actions\BulkAction;
use Workbench\App\Models\Product;

it('can instantiate an bulk action', function () {
    $action = new BulkAction('Create');
    expect($action)->toBeInstanceOf(BulkAction::class)
        ->getLabel()->toBe('Create')
        ->getName()->toBe('create')
        ->getType()->toBe('bulk')
        ->isAuthorised()->toBeTrue()
        ->canAction()->toBeFalse()
        ->getMeta()->toBe([])
        ->isConfirmable()->toBeFalse()
        ->hasConfirmTitle()->toBeFalse()
        ->hasConfirmMessage()->toBeFalse()
        ->hasConfirmType()->toBeFalse()
        ->isInline()->toBeFalse()
        ->isDeselectable()->toBeTrue()
        ->hasChunkById()->toBeFalse()
        ->HasChunkSize()->toBeFalse()
        ->getChunkById()->toBe(config('table.chunk.by_id'))
        ->getChunkSize()->toBe(config('table.chunk.size'));
});

describe('base', function () {
    beforeEach(function () {
        $this->action = BulkAction::make('Create');
    });

    it('can make an bulk action', function () {
        expect($this->action)->toBeInstanceOf(BulkAction::class)
            ->getLabel()->toBe('Create')
            ->getName()->toBe('create')
            ->getType()->toBe('bulk')
            ->isAuthorised()->toBeTrue()
            ->canAction()->toBeFalse()
            ->hasMeta()->toBeFalse()
            ->isConfirmable()->toBeFalse()
            ->hasConfirmTitle()->toBeFalse()
            ->hasConfirmMessage()->toBeFalse()
            ->hasConfirmType()->toBeFalse()
            ->isInline()->toBeFalse()
            ->isDeselectable()->toBeTrue()
            ->hasChunkById()->toBeFalse()
            ->HasChunkSize()->toBeFalse()
            ->getChunkById()->toBe(config('table.chunk.by_id'))
            ->getChunkSize()->toBe(config('table.chunk.size'));

    });

    it('has array form', function () {
        expect($this->action->toArray())->toEqual([
            'name' => 'create',
            'label' => 'Create',
            'type' => 'bulk',
            'meta' => [],
            'confirm' => null,
            'deselect' => true,
        ]);
    });
});

describe('chained', function () {
    beforeEach(function () {
        $this->action = BulkAction::make('Create')
            ->name('make')
            ->meta(['key' => 'value'])
            ->authorize(fn () => false)
            ->confirmable()
            ->confirmTitle('Are you sure?')
            ->confirmMessage('This action is irreversible')
            ->confirmType('destructive')
            ->inline()
            ->deselectable()
            ->chunk(1000, false)
            ->action(fn (Product $product) => $product->create());
    });

    it('can make an bulk action', function () {
        expect($this->action)->toBeInstanceOf(BulkAction::class)
            ->getLabel()->toBe('Create')
            ->getName()->toBe('make')
            ->getType()->toBe('bulk')
            ->isAuthorised()->toBeFalse()
            ->canAction()->toBeTrue()
            ->hasMeta()->toBeTrue()
            ->isConfirmable()->tobeTrue()
            ->hasConfirmTitle()->tobeTrue()
            ->hasConfirmMessage()->tobeTrue()
            ->hasConfirmType()->tobeTrue()
            ->isInline()->toBeTrue()
            ->isDeselectable()->toBeFalse()
            ->hasChunkById()->toBeTrue()
            ->HasChunkSize()->toBeTrue()
            ->getChunkById()->toBe(false)
            ->getChunkSize()->toBe(1000);
    });

    it('has array form', function () {
        expect($this->action->toArray())->toEqual([
            'name' => 'make',
            'label' => 'Create',
            'type' => 'bulk',
            'meta' => [
                'key' => 'value',
            ],
            'confirm' => [
                'title' => 'Are you sure?',
                'message' => 'This action is irreversible',
                'type' => 'destructive',
            ],
            'deselect' => false,
        ]);
    });
});

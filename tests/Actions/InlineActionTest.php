<?php

use Conquest\Table\Actions\InlineAction;
use Workbench\App\Models\Product;

// it('testing', function () {
//     $action = InlineAction::make('Create')->confirm->title('Hello');
//     dd($action, $action->getConfirm());
// });

it('can instantiate an inline action', function () {
    $action = new InlineAction('Create');
    expect($action)->toBeInstanceOf(InlineAction::class)
        ->getLabel()->toBe('Create')
        ->getName()->toBe('create')
        ->getType()->toBe('inline')
        ->isAuthorised()->toBeTrue()
        ->hasRoute()->toBeFalse()
        ->lacksMethod()->toBeTrue()
        ->canAction()->toBeFalse()
        ->getMeta()->toBe([])
        ->isDefault()->toBeFalse()
        ->isConfirmable()->toBeFalse()
        ->hasConfirmTitle()->toBeFalse()
        ->hasConfirmMessage()->toBeFalse()
        ->hasConfirmType()->toBeFalse()
        ->isBulk()->toBeFalse();
});

describe('base', function () {
    beforeEach(function () {
        $this->action = InlineAction::make('Create');
    });

    it('can make an inline action', function () {
        expect($this->action)->toBeInstanceOf(InlineAction::class)
            ->getLabel()->toBe('Create')
            ->getName()->toBe('create')
            ->getType()->toBe('inline')
            ->isAuthorised()->toBeTrue()
            ->hasRoute()->toBeFalse()
            ->lacksMethod()->toBeTrue()
            ->canAction()->toBeFalse()
            ->hasMeta()->toBeFalse()
            ->isDefault()->toBeFalse()
            ->isConfirmable()->toBeFalse()
            ->hasConfirmTitle()->toBeFalse()
            ->hasConfirmMessage()->toBeFalse()
            ->hasConfirmType()->toBeFalse()
            ->isBulk()->toBeFalse();
    });

    it('has array form', function () {
        expect($this->action->toArray())->toEqual([
            'name' => 'create',
            'label' => 'Create',
            'type' => 'inline',
            'meta' => [],
            'confirm' => null,
            'route' => null,
            'method' => null,
            'actionable' => false,
        ]);
    });
});

describe('chained', function () {
    beforeEach(function () {
        $this->action = InlineAction::make('Create')
            ->name('make')
            ->meta(['key' => 'value'])
            ->authorize(fn () => false)
            ->default()
            ->confirmable()
            ->confirmTitle('Are you sure?')
            ->confirmMessage('This action is irreversible')
            ->confirmType('destructive')
            ->bulk()
            ->route('/products')
            ->method('POST')
            ->action(fn (Product $product) => $product->create());
    });

    it('can make an inline action', function () {
        expect($this->action)->toBeInstanceOf(InlineAction::class)
            ->getLabel()->toBe('Create')
            ->getName()->toBe('make')
            ->getType()->toBe('inline')
            ->isAuthorised()->toBeFalse()
            ->hasRoute()->toBeTrue()
            ->lacksMethod()->toBeFalse()
            ->canAction()->toBeTrue()
            ->hasMeta()->toBeTrue()
            ->isDefault()->toBeTrue()
            ->isConfirmable()->tobeTrue()
            ->hasConfirmTitle()->tobeTrue()
            ->hasConfirmMessage()->tobeTrue()
            ->hasConfirmType()->tobeTrue()
            ->isBulk()->toBeTrue();
    });

    it('has array form', function () {
        expect($this->action->toArray())->toEqual([
            'name' => 'make',
            'label' => 'Create',
            'type' => 'inline',
            'meta' => [
                'key' => 'value',
            ],
            'route' => url('/products'),
            'method' => 'POST',
            'confirm' => [
                'title' => 'Are you sure?',
                'message' => 'This action is irreversible',
                'type' => 'destructive',
            ],
            'actionable' => true,
        ]);
    });
});

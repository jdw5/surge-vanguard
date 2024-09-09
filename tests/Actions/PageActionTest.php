<?php

use Conquest\Table\Actions\PageAction;

it('can instantiate an page action', function () {
    $action = new PageAction('Create');
    expect($action)->toBeInstanceOf(PageAction::class)
        ->getLabel()->toBe('Create')
        ->getName()->toBe('create')
        ->getType()->toBe('page')
        ->isAuthorised()->toBeTrue()
        ->hasRoute()->toBeFalse()
        ->lacksMethod()->toBeTrue()
        ->getMeta()->toBe([]);
});

describe('base', function () {
    beforeEach(function () {
        $this->action = PageAction::make('Create');
    });

    it('can make an page action', function () {
        expect($this->action)->toBeInstanceOf(PageAction::class)
            ->getLabel()->toBe('Create')
            ->getName()->toBe('create')
            ->getType()->toBe('page')
            ->isAuthorised()->toBeTrue()
            ->hasRoute()->toBeFalse()
            ->lacksMethod()->toBeTrue()
            ->hasMeta()->toBeFalse();
    });

    it('has array form', function () {
        expect($this->action->toArray())->toEqual([
            'name' => 'create',
            'label' => 'Create',
            'type' => 'page',
            'meta' => [],
            'route' => null,
            'method' => null,
        ]);
    });
});

describe('chained', function () {
    beforeEach(function () {
        $this->action = PageAction::make('Create')
            ->name('make')
            ->meta(['key' => 'value'])
            ->authorize(fn () => false)
            ->route('/products')
            ->method('POST');
    });

    it('can make an page action', function () {
        expect($this->action)->toBeInstanceOf(PageAction::class)
            ->getLabel()->toBe('Create')
            ->getName()->toBe('make')
            ->getType()->toBe('page')
            ->isAuthorised()->toBeFalse()
            ->hasRoute()->toBeTrue()
            ->lacksMethod()->toBeFalse()
            ->hasMeta()->toBeTrue();
    });

    it('has array form', function () {
        expect($this->action->toArray())->toEqual([
            'name' => 'make',
            'label' => 'Create',
            'type' => 'page',
            'meta' => [
                'key' => 'value',
            ],
            'route' => url('/products'),
            'method' => 'POST',
        ]);
    });
});

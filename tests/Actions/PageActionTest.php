<?php

use Conquest\Table\Table;
use Conquest\Table\Actions\PageAction;

it('can create a page action', function () {
    $action = new PageAction($l = 'Page');
    expect($action->getLabel())->toBe($l);
    expect($action->getName())->toBe('page');
    expect($action->isAuthorised())->toBeTrue();
    expect($action->getType())->toBe(Table::PAGE_ACTION);
    expect($action->getResolvedRoute())->toBeNull();
    expect($action->getMethod())->toBeNull();
    expect($action->getMeta())->toBe([]);
});

it('can make a page action', function () {
    expect(PageAction::make('Page'))->toBeInstanceOf(PageAction::class)
        ->getLabel()->toBe('Page')
        ->getName()->toBe('page')
        ->getType()->toBe(Table::PAGE_ACTION);
});

it('can set a named route on a page action', function () {
    $action = PageAction::make('Page', route: 'page.index');
    expect($action->getResolvedRoute())->toBe(route('page.index'));
});

it('can set a uri route on a page action', function () {
    expect(PageAction::make('Page', route: '/page')->getResolvedRoute())->toBe(url('/page'));
});

it('can set a resolvable named route on a page action', function () {
    expect(PageAction::make('Page', route: fn () => 'page.index')->getResolvedRoute())->toBe(route('page.index'));
});

it('can set a resolvable uri route on a page action', function () {
    expect(PageAction::make('Page', route: fn () => '/page')->getResolvedRoute())->toBe(url('/page'));
});

it('can set a method on a page action', function () {
    expect(PageAction::make('Page', method: 'POST')->getMethod())->toBe('POST');
});

it('can set method directly on a page action', function () {
    expect(PageAction::make('Page')->usePatch()->getMethod())->toBe('PATCH');
});
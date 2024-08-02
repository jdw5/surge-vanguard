<?php

use Conquest\Table\Actions\InlineAction;
use Conquest\Table\Table;
use Workbench\App\Models\Product;

it('can create an inline action', function () {
    $action = new InlineAction($l = 'Create');
    expect($action->getLabel())->toBe($l);
    expect($action->getName())->toBe('create');
    expect($action->isAuthorised())->toBeTrue();
    expect($action->getResolvedRoute())->toBeNull();
    expect($action->getMethod())->toBeNull();
    expect($action->getType())->toBe(Table::INLINE_ACTION);
    expect($action->canAction())->toBeFalse();
    expect($action->getMeta())->toBe([]);
    expect($action->getConfirmation())->toBeNull();
    expect($action->isDefault())->toBeFalse();
});

it('can make an inline action', function () {
    expect(InlineAction::make('Create'))->toBeInstanceOf(InlineAction::class)
        ->getLabel()->toBe('Create')
        ->getName()->toBe('create')
        ->getType()->toBe(Table::INLINE_ACTION);
});

it('can be made default', function () {
    expect(InlineAction::make('Create')->default()->isDefault())->toBeTrue();
    expect(InlineAction::make('Create', default: fn () => true)->isDefault())->toBeTrue();
});

it('can set a confirmation message', function () {
    expect(InlineAction::make('Delete', confirmation: $m = 'Are you sure?')->getConfirmation())
        ->toBe($m);
});

it('can set a confirmation message using the action name', function () {
    expect(InlineAction::make('Delete', confirmation: fn (string $name) => "Are you want to $name this product?")->getConfirmation())
        ->toBe('Are you want to delete this product?');
});

it('can set a confirmation message using the action', function () {
    expect(InlineAction::make('Delete', confirmation: fn ($action) => 'Are you want to '.$action->getLabel().' this product?')->getConfirmation())
        ->toBe('Are you want to Delete this product?');
});

it('can set a default confirmation message', function () {
    expect(InlineAction::make('Delete')->confirmation()->getConfirmation())
        ->not->toBeNull();
});

it('can set a confirmation message using a record', function () {
    $product = Product::factory()->create([
        'category_id' => 1,
    ]);
    $action = InlineAction::make('Delete', confirmation: fn (Product $product) => "Are you want to delete {$product->name}?");
    $action->resolveConfirmation($product, Product::class);
    expect($action->getConfirmation($product))
        ->toBe('Are you want to delete '.$product->name.'?');
});

it('can have handler', function () {
    expect(InlineAction::make('Delete', action: fn (Product $product) => $product->delete())->canAction())
        ->toBeTrue();
});

it('can apply an action', function () {
    $action = InlineAction::make('Delete', action: fn (Product $product) => $product->delete());
    $product = Product::factory()->create([
        'category_id' => 1,
    ]);
    expect(Product::find($product->id))->not->toBeNull();
    $action->applyAction(Product::class, $product);
    expect(Product::find($product->id))->toBeNull();
});

it('can set a named route on action', function () {
    $action = InlineAction::make('Page', route: 'page.index');
    expect($action->getResolvedRoute())->toBe(route('page.index'));
});

it('can set a resolvable named route on a page action', function () {
    expect(InlineAction::make('Page', route: fn () => 'page.index')->getResolvedRoute())->toBe(route('page.index'));
});

it('can set a resolvable uri route on a page action', function () {
    expect(InlineAction::make('Page', route: fn () => '/page')->getResolvedRoute())->toBe(url('/page'));
});

it('can set a uri route on action', function () {
    expect(InlineAction::make('Page', route: '/page')->getResolvedRoute())->toBe(url('/page'));
});

it('can set a method on action', function () {
    expect(InlineAction::make('Page', method: 'POST')->getMethod())->toBe('POST');
});

it('can set method directly on action', function () {
    expect(InlineAction::make('Page')->usePost()->getMethod())->toBe('POST');
});

it('can resolve route using a model', function () {
    $product = Product::find(1);
    $action = InlineAction::make('Show', route: 'product.show');
    expect($action->getResolvedRoute($product))->toBe(route('product.show', $product));
});

// it('can set a rout')/

<?php

use Conquest\Table\Table;
use Conquest\Table\Actions\BulkAction;
use Workbench\App\Models\Product;

it('can create a bulk action', function () {
    $action = new BulkAction($l = 'Delete');
    expect($action->getLabel())->toBe($l);
    expect($action->getName())->toBe('delete');
    expect($action->isAuthorised())->toBeTrue();
    expect($action->getType())->toBe(Table::BULK_ACTION);
    expect($action->hasAction())->toBeFalse();
    expect($action->getChunkMethod())->toBe('chunkById');
    expect($action->getChunkSize())->toBe(500);
    expect($action->getMetadata())->toBe([]);
    expect($action->getConfirmation())->toBeNull();
});

it('can make a bulk action', function () {
    expect(BulkAction::make('Delete'))->toBeInstanceOf(BulkAction::class)
        ->getLabel()->toBe('Delete')
        ->getName()->toBe('delete')
        ->getType()->toBe(Table::BULK_ACTION);
});

it('can change chunking parameters', function () {
    expect(BulkAction::make('Delete', chunkSize: 100, chunkById: false))
        ->getChunkSize()->toBe(100)
        ->getChunkMethod()->toBe('chunk');
});

it('can set a confirmation message', function () {
    expect(BulkAction::make('Delete', confirmation: $m ='Are you sure?')->getConfirmation())
        ->toBe($m);
});

it('can set a confirmation message using the action name', function () {
    expect(BulkAction::make('Delete', confirmation: fn (string $name) => "Are you want to $name this product?")->getConfirmation())
        ->toBe('Are you want to delete this product?');
});

it('can set a confirmation message using the action', function () {
    expect(BulkAction::make('Delete', confirmation: fn ($action) => "Are you want to ".$action->getLabel() ." this product?")->getConfirmation())
        ->toBe('Are you want to Delete this product?');
});

it('can set a default confirmation message', function () {
    expect(BulkAction::make('Delete')->confirmation()->getConfirmation())
        ->not->toBeNull();
});

it('can have handler', function () {
    expect(BulkAction::make('Delete', action: fn (Product $product) => $product->delete())->hasAction())
        ->toBeTrue();
});

it('can apply an action', function () {
    $action = BulkAction::make('Delete', action: fn (Product $product) => $product->delete());
    $product = Product::factory()->create([
        'category_id' => 1,
    ]);
    expect(Product::find($product->id))->not->toBeNull();
    $action->applyAction(Product::class, $product);
    expect(Product::find($product->id))->toBeNull();
});
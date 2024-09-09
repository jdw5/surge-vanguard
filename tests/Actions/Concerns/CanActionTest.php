<?php

use Conquest\Table\Actions\InlineAction;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Model;
use Workbench\App\Models\Product;

beforeEach(function () {
    $this->action = InlineAction::make('Delete');
});

it('has no action by default', function () {
    expect($this->action->canAction())->toBeFalse();
    expect($this->action->cannotAction())->toBeTrue();
});

it('can set action', function () {
    $this->action->setAction(fn (Product $product) => $product->name = 'New name');
    expect($this->action->canAction())->toBeTrue();
    expect($this->action->cannotAction())->toBeFalse();
});

it('can set action alias', function () {
    $this->action->each(fn (Product $product) => $product->name = 'New name');
    expect($this->action->canAction())->toBeTrue();
    expect($this->action->cannotAction())->toBeFalse();
});

it('can set as invokable class', function () {
    // $this->action->setAction(Product::class);
    // expect($this->action->getAction())->toBeInstanceOf(Product::class);
})->skip();

it('can set action through chaining', function () {
    expect($this->action->action(fn (Product $product) => $product->name = 'New name'))
        ->toBeInstanceOf(InlineAction::class)
        ->canAction()->toBeTrue()
        ->cannotAction()->toBeFalse();
});

it('prevents null action from being set', function () {
    $this->action->setAction(null);
    expect($this->action)
        ->canAction()->toBeFalse()
        ->cannotAction()->toBeTrue();
});

it('can apply action using typed model class', function () {
    $this->action->setAction(fn (Product $product) => $product->name = 'New name');
    $product = Product::factory()->create();
    $this->action->applyAction(Product::class, $product);
    expect($product->name)->toBe('New name');
});

it('can apply action using typed model', function () {
    $this->action->setAction(fn (Model $product) => $product->name = 'New name');
    $product = Product::factory()->create();
    $this->action->applyAction(Product::class, $product);
    expect($product->name)->toBe('New name');
});

it('can apply action using named record', function () {
    $this->action->setAction(fn ($record) => $record->name = 'New name');
    $product = Product::factory()->create();
    $this->action->applyAction(Product::class, $product);
    expect($product->name)->toBe('New name');
});

it('does not resolve arguments which are not typed or named', function () {
    $this->action->setAction(fn ($product) => $product->name = 'New name');
    $product = Product::factory()->create();
    expect($this->action->applyAction(Product::class, $product));
})->throws(BindingResolutionException::class);

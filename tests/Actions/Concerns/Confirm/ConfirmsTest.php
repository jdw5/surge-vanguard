<?php

use Conquest\Table\Actions\BulkAction;
use Conquest\Table\Actions\Concerns\Confirm\ConfirmType;

beforeEach(function () {
    $this->action = BulkAction::make('Delete');
});

it('can confirm using defaults', function () {
    expect($this->action->confirm())->toBeInstanceOf(BulkAction::class)
        ->getConfirmType()->toBeNull()
        ->getConfirmTitle()->toBe(config('table.confirm.title'))
        ->getConfirmMessage()->toBe(config('table.confirm.message'))
        ->isConfirmable()->toBeTrue();
});

it('can confirm with parameters', function () {
    expect($this->action->confirm(ConfirmType::Destructive, 'Delete', 'Are you sure you want to delete this product?'))->toBeInstanceOf(BulkAction::class)
        ->getConfirmType()->toBe(ConfirmType::Destructive->value)
        ->getConfirmTitle()->toBe('Delete')
        ->getConfirmMessage()->toBe('Are you sure you want to delete this product?')
        ->isConfirmable()->toBeTrue();
});

it('has empty values if not confirmable', function () {
    expect($this->action->toArrayConfirm())->toEqual(['confirm' => null]);
});

it('has confirm values if confirmable', function () {
    $this->action->confirmable();
    expect($this->action->toArrayConfirm())->toEqual([
        'confirm' => [
            'type' => null,
            'title' => config('table.confirm.title'),
            'message' => config('table.confirm.message'),
        ],
    ]);
});

it('has confirm values if type set', function () {
    $this->action->confirmType(ConfirmType::Destructive);
    expect($this->action->toArrayConfirm())->toEqual([
        'confirm' => [
            'type' => ConfirmType::Destructive->value,
            'title' => config('table.confirm.title'),
            'message' => config('table.confirm.message'),
        ],
    ]);
});

it('has confirm values if confirm message set', function () {
    $this->action->confirmMessage('Are you sure you want to delete this product?');
    expect($this->action->toArrayConfirm())->toEqual([
        'confirm' => [
            'type' => null,
            'title' => config('table.confirm.title'),
            'message' => 'Are you sure you want to delete this product?',
        ],
    ]);
});

it('has confirm values if confirm title set', function () {
    $this->action->confirmTitle('Delete');
    expect($this->action->toArrayConfirm())->toEqual([
        'confirm' => [
            'type' => null,
            'title' => 'Delete',
            'message' => config('table.confirm.message'),
        ],
    ]);
});

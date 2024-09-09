<?php

use Conquest\Table\Filters\Enums\Clause;
use Conquest\Table\Filters\Filter;

beforeEach(function () {
    $this->filter = Filter::make('name');
});

it('has is clause by default', function () {
    expect($this->filter->getClause())->toBe(Clause::Is);
    expect($this->filter->hasClause())->toBeTrue();
    expect($this->filter->lacksClause())->toBeFalse();
});

it('can set a clause', function () {
    $this->filter->setClause(Clause::IsNot);
    expect($this->filter->getClause())->toBe(Clause::IsNot);
    expect($this->filter->hasClause())->toBeTrue();
    expect($this->filter->lacksClause())->toBeFalse();
});

it('can set a clause through chaining', function () {
    expect($this->filter->clause(Clause::IsNot))->toBeInstanceOf(Filter::class)
        ->getClause()->toBe(Clause::IsNot)
        ->hasClause()->toBeTrue()
        ->lacksClause()->toBeFalse();
});

it('prevents null behaviour from being set', function () {
    $this->filter->setClause(null);
    expect($this->filter)
        ->getClause()->toBe(Clause::Is)
        ->hasClause()->toBeTrue()
        ->lacksClause()->toBeFalse();
});

it('can set "is" clause through shorthand chain', function () {
    expect($this->filter->is())->toBeInstanceOf(Filter::class)
        ->getClause()->toBe(Clause::Is);
});

it('can set "is not" clause through shorthand chain', function () {
    expect($this->filter->isNot())->toBeInstanceOf(Filter::class)
        ->getClause()->toBe(Clause::IsNot);
});

it('can set "starts with" clause through shorthand chain', function () {
    expect($this->filter->startsWith())->toBeInstanceOf(Filter::class)
        ->getClause()->toBe(Clause::StartsWith);
});

it('has alias for "starts with"', function () {
    expect($this->filter->beginsWith())->toBeInstanceOf(Filter::class)
        ->getClause()->toBe(Clause::StartsWith);
});

it('can set "ends with" clause through shorthand chain', function () {
    expect($this->filter->endsWith())->toBeInstanceOf(Filter::class)
        ->getClause()->toBe(Clause::EndsWith);
});

it('can set "contains" clause through shorthand chain', function () {
    expect($this->filter->contains())->toBeInstanceOf(Filter::class)
        ->getClause()->toBe(Clause::Contains);
});

it('can set "does not contain" clause through shorthand chain', function () {
    expect($this->filter->doesNotContain())->toBeInstanceOf(Filter::class)
        ->getClause()->toBe(Clause::DoesNotContain);
});

it('can set "all" clause through shorthand chain', function () {
    expect($this->filter->all())->toBeInstanceOf(Filter::class)
        ->getClause()->toBe(Clause::All);
});

it('can set "any" clause through shorthand chain', function () {
    expect($this->filter->any())->toBeInstanceOf(Filter::class)
        ->getClause()->toBe(Clause::Any);
});

it('can set "json" clause through shorthand chain', function () {
    expect($this->filter->json())->toBeInstanceOf(Filter::class)
        ->getClause()->toBe(Clause::Json);
});

it('can set "not json" clause through shorthand chain', function () {
    expect($this->filter->notJson())->toBeInstanceOf(Filter::class)
        ->getClause()->toBe(Clause::NotJson);
});

it('can set "json length" clause through shorthand chain', function () {
    expect($this->filter->jsonLength())->toBeInstanceOf(Filter::class)
        ->getClause()->toBe(Clause::JsonLength);
});

it('can set "full text" clause through shorthand chain', function () {
    expect($this->filter->fullText())->toBeInstanceOf(Filter::class)
        ->getClause()->toBe(Clause::FullText);
});

it('can set "search" clause through shorthand chain', function () {
    expect($this->filter->search())->toBeInstanceOf(Filter::class)
        ->getClause()->toBe(Clause::Search);
});

it('can set "json key" clause through shorthand chain', function () {
    expect($this->filter->jsonKey())->toBeInstanceOf(Filter::class)
        ->getClause()->toBe(Clause::JsonKey);
});

it('can set "not json key" clause through shorthand chain', function () {
    expect($this->filter->notJsonKey())->toBeInstanceOf(Filter::class)
        ->getClause()->toBe(Clause::JsonNotKey);
});

it('can set "json overlap" clause through shorthand chain', function () {
    expect($this->filter->jsonOverlap())->toBeInstanceOf(Filter::class)
        ->getClause()->toBe(Clause::JsonOverlaps);
});

it('has alias for "json overlap"', function () {
    expect($this->filter->jsonOverlaps())->toBeInstanceOf(Filter::class)
        ->getClause()->toBe(Clause::JsonOverlaps);
});

it('can set "json does not overlap" clause through shorthand chain', function () {
    expect($this->filter->jsonDoesNotOverlap())->toBeInstanceOf(Filter::class)
        ->getClause()->toBe(Clause::JsonDoesNotOverlap);
});

it('can set "like" clause through shorthand chain', function () {
    expect($this->filter->like())->toBeInstanceOf(Filter::class)
        ->getClause()->toBe(Clause::Like);
});

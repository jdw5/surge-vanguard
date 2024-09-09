<?php

use Conquest\Table\Pagination\Pagination;
use Conquest\Table\Table;

uses(Table::class);

it('returns default per page when no per page is set', function () {
    $this->setDefaultPerPage(15);
    expect($this->getPaginationCount())->toBe(15);
});

it('returns set per page when it is set', function () {
    $this->setPerPage(20);
    expect($this->getPaginationCount())->toBe(20);
});

it('sets pagination correctly', function () {
    $this->setPagination(25);
    expect($this->getPerPage())->toBe(25);
});

it('does not set pagination when null is passed', function () {
    $this->setPerPage(30);
    $this->setPagination(null);
    expect($this->getPerPage())->toBe(30);
});

it('returns single pagination option when per page is an integer', function () {
    $this->setPerPage(10);
    $pagination = $this->getPagination();
    expect($pagination)->toHaveCount(1)
        ->and($pagination[0])->toBeInstanceOf(Pagination::class)
        ->and($pagination[0]->getValue())->toBe(10)
        ->and($pagination[0]->isActive())->toBeTrue();
});

it('returns multiple pagination options when per page is an array', function () {
    $this->setPerPage([10, 20, 30]);
    $pagination = $this->getPagination(20);
    expect($pagination)->toHaveCount(3)
        ->and($pagination[0]->getValue())->toBe(10)
        ->and($pagination[0]->isActive())->toBeFalse()
        ->and($pagination[1]->getValue())->toBe(20)
        ->and($pagination[1]->isActive())->toBeTrue()
        ->and($pagination[2]->getValue())->toBe(30)
        ->and($pagination[2]->isActive())->toBeFalse();
});

it('uses correct per page value', function () {
    $this->setPerPage([10, 20, 30]);
    $this->setDefaultPerPage(10);

    // Mock getPerPageFromRequest to return 20
    $this->mock(Table::class, function ($mock) {
        $mock->shouldReceive('getPerPageFromRequest')->andReturn(20);
    });

    expect($this->usePerPage())->toBe(20);
});

it('falls back to default per page when requested value is not in options', function () {
    $this->setPerPage([10, 20, 30]);
    $this->setDefaultPerPage(10);

    // Mock getPerPageFromRequest to return 40 (not in options)
    $this->mock(Table::class, function ($mock) {
        $mock->shouldReceive('getPerPageFromRequest')->andReturn(40);
    });

    expect($this->usePerPage())->toBe(10);
});

<?php

use Conquest\Table\Table;
use Workbench\App\Models\Product;
use Conquest\Table\Columns\Column;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Request;

beforeEach(function () {
    $this->table = Table::make(
        resource: Product::query(),
        columns: [
            Column::make('id')->key(),
        ],
    );
});

it('uses config defaults', function () {
    expect($this->table->remembers())->toBe(config('table.remember.default'));
    expect($this->table->getToggleKey())->toBe(config('table.remember.toggle_key'));
    expect($this->table->hasCookie())->toBe(config('table.remember.cookie'));
    expect($this->table->getRememberFor())->toBe(config('table.remember.duration'));
});

it('can set remember parameters', function () {
    expect($this->table->remember($r = 'r', 60, $t = 't', true))
        ->remembers()->toBeTrue()
        ->getCookieName()->toBe($r)
        ->getRememberFor()->toBe(60)
        ->getToggleKey()->toBe($t)
        ->hasCookie()->toBeTrue();
});

it('can encode a cookie from data', function () {
    $this->table->setCookieName('remember');
    $this->table->encodeCookie(['a', 'b', 'c']);
    $queuedCookies = Cookie::getQueuedCookies();
    expect($queuedCookies)->toHaveCount(1);
    expect(collect($queuedCookies)->first())
        ->getValue()->toBe(json_encode(['a', 'b', 'c']))
        ->getName()->toBe('remember');
});

it('can decode data from a cookie', function () {    
    $request = new HttpRequest();
    $request->cookies->set($this->table->getCookieName(), json_encode($cookieValue = ['a', 'b', 'c']));
    $this->app->instance('request', $request);
    expect($this->table->decodeCookie())->toBe($cookieValue);
});

it('can retrieve columns from request', function () {
    Request::merge([$this->table->getToggleKey() => 'a,b,c']);
    expect($this->table->getRememberedFromRequest())->toBe(['a', 'b', 'c']);
});

it('can retrieve columns from request using custom key', function () {
    $this->table->setToggleKey('custom');
    Request::merge(['custom' => 'a,b,c']);
    expect($this->table->getRememberedFromRequest())->toBe(['a', 'b', 'c']);
});

// Test toggleability here
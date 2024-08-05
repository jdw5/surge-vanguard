<?php

use Conquest\Table\Actions\BulkAction;
use Conquest\Table\Table;
use Workbench\App\Models\Product;

beforeEach(function () {
    $this->table = Table::make();
});

it('uses show key default', function (){
    expect($this->table->getShowKey())->toBe(config('table.pagination.key'));
});

it('can set a show key', function () {
    $this->table->setShowKey('count');
    expect($this->table->getShowKey())->toBe('count');
});
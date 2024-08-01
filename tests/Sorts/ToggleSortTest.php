<?php

use Conquest\Table\Sorts\ToggleSort;

it('can create a toggle sort', function () {
    $sort = new ToggleSort($n = 'name');
    expect($sort->getProperty())->toBe($n);
    expect($sort->getName())->toBe($n);
    expect($sort->getLabel())->toBe('Name');
    expect($sort->isAuthorised())->toBeTrue();
    // expect($sort->getDirection())->toBe('asc');
    expect($sort->hasMeta())->toBeFalse();
});

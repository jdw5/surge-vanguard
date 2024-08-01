<?php

use Conquest\Table\Columns\Column;

beforeEach(function () {
    $this->column = Column::make('name');
});

// it('does not format money by default', function () {
//     expect($this->column->formatsSeparator())->toBeFalse();
//     expect($this->column->getSeparator())->toBeNull();
// });

// it('can set as separator', function () {
//     $this->column->setPrefix('Mr');
//     expect($this->column->getPrefix())->toBe('Mr');
//     expect($this->column->hasPrefix())->toBeTrue();
//     expect($this->column->lacksPrefix())->toBeFalse();
// });

// it('can set a resolvable prefix', function () {
//     $this->column->setPrefix(fn () => 'Mr');
//     expect($this->column->getPrefix())->toBe('Mr');
//     expect($this->column->hasPrefix())->toBeTrue();
//     expect($this->column->lacksPrefix())->toBeFalse();
// });

// it('can set a prefix through chaining', function () {
//     expect($this->column->prefix('Mr'))->toBeInstanceOf(Column::class)
//         ->getPrefix()->toBe('Mr')
//         ->hasPrefix()->toBeTrue()
//         ->lacksPrefix()->toBeFalse();
// });

// it('prevents null behaviour from being set', function () {
//     $this->column->setPrefix(null);
//     expect($this->column)
//         ->getPrefix()->toBeNull()
//         ->hasPrefix()->toBeFalse()
//         ->lacksPrefix()->toBeTrue();
// });
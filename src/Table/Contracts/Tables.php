<?php

namespace Jdw5\Vanguard\Table\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Collection;

interface Tables
{
    public static function make(Builder|QueryBuilder $data = null): static;

    public function pipeline(): void;

    public function getMeta(): array;

    public function getRecords(): Collection;
}
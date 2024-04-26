<?php

namespace Jdw5\Vanguard\Table\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

interface Tables
{
    public static function make($data = null): static;

    public function pipeline(): void;

    public function getMeta(): array;

    public function getRecords(): Collection;
}
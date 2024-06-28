<?php

namespace Conquest\Table\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Collection;

interface Tables
{
    public function getMeta(): array;
    
    public function getRecords(): ?array;
}
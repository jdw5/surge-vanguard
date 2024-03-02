<?php

namespace Jdw5\SurgeVanguard\Table\Contracts;

use Illuminate\Database\Eloquent\Builder;


interface Tables
{
    public static function make(Builder $data): static;
}
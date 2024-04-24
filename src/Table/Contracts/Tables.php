<?php

namespace Jdw5\Vanguard\Table\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface Tables
{
    public static function make($data = null): static;

    public function tablePipeline(): void;

    public function getMeta(): array;

    public function getRecords(): array;
}
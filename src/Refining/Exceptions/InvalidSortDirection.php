<?php

namespace Jdw5\Vanguard\Refining\Sorts;

class InvalidSortDirection extends \Exception
{
    public function __construct(string $direction)
    {
        parent::__construct("Invalid sort direction: {$direction}");
    }
}
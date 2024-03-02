<?php

namespace Jdw5\SurgeVanguard\Refining\Sorts;

class InvalidSortDirection extends \Exception
{
    public function __construct(string $direction)
    {
        parent::__construct("Invalid sort direction: {$direction}");
    }
}
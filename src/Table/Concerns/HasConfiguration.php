<?php

namespace Jdw5\Vanguard\Table\Concerns;

/**
 * Trait HasConfiguration
 * 
 * Store core configuration parameters for the table
 */
trait HasConfiguration
{
    protected $applyColumns = true;
    protected $applyActions = true;
    // Provide a method to applyColumns without dropping

    protected $recordReservedTerms = [
        'method',
        'action',
    ];
}


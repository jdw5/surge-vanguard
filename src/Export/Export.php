<?php

namespace Jdw5\Vanguard\Export;

use Closure;
use Jdw5\Vanguard\Concerns\HasLabel;
use Jdw5\Vanguard\Primitive;
use Jdw5\Vanguard\Export\Exports;

class Export extends Primitive implements Exports 
{
    use HasLabel;

    public function __construct(
        string $label, 
        string $filename = null,
        // type = null,
        Closure|bool $authorize = true,
    ) {
        $this->setLabel($label);
        // $this-
    }
    
    public function export()
    {
        return [];
    }

    public function jsonSerialize(): mixed
    {
        
    }
}
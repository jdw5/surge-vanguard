<?php

namespace Jdw5\Vanguard\Concerns;

trait HasSort
{
    // Check if $defaultSort is defined
    
    public function getSorts()
    {
        if (isset($this->sorts)) {
            return $this->sorts;
        }

        if (function_exists('sorts')) {
            return $this->sorts();
        }

        return [];
    }
}
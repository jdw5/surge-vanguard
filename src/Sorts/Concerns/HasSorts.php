<?php

namespace Jdw5\Vanguard\Sorts\Concerns;

trait HasSorts
{
    protected array $sorts;

    protected function setSorts(array|null $sorts): void
    {
        if (is_null($sorts)) return;
        $this->sorts = $sorts;
    }
    
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
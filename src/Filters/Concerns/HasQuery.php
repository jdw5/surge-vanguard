<?php

namespace Conquest\Table\Filters\Concerns;

use Closure;
use Conquest\Table\Exceptions\QueryNotDefined;

trait HasQuery
{
    protected Closure $query;

    public function query(Closure $query): static
    {
        $this->setQuery($query);

        return $this;
    }

    public function using(Closure $query): static
    {
        return $this->query($query);
    }

    protected function setQuery(?Closure $query): void
    {
        if (is_null($query)) return;
        $this->query = $query;
    }

    public function getQuery(): Closure
    {
        if (! isset($this->query)) {
            throw new QueryNotDefined();
        }

        return $this->query;
    }


}

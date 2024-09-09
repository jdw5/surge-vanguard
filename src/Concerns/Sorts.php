<?php

namespace Conquest\Table\Concerns;

use Conquest\Table\Sorts\BaseSort;
use Conquest\Table\Sorts\Concerns\HasOrder;
use Conquest\Table\Sorts\Concerns\HasSort;
use Conquest\Table\Sorts\Sort;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

trait Sorts
{
    use HasOrder;
    use HasSort;

    protected array $sorts;

    public function getSorts()
    {
        if (isset($this->sorts)) {
            return $this->sorts;
        }

        if (method_exists($this, 'sorts')) {
            return $this->sorts();
        }

        return [];
    }

    protected function setSorts(?array $sorts): void
    {
        if (is_null($sorts)) {
            return;
        }
        $this->sorts = $sorts;
    }

    public function getDefaultSort(): ?Sort
    {
        return collect($this->getSorts())->first(fn ($sort) => $sort->isDefault());
    }

    public function sorting(): bool
    {
        return ! is_null($this->getSort());
    }

    /**
     * Apply the sorting to the query
     *
     * @param  array<BaseSort>  $sorts
     */
    protected function sort(Builder|QueryBuilder $query, array $sorts): void
    {
        if ($this->sorting()) {
            foreach ($sorts as $sort) {
                $sort->apply($query, $this->getSort(), $this->getOrder());
                if ($sort->isActive()) {
                    break;
                }
            }
        } else {
            $this->getDefaultSort()?->handle($query);
        }
    }
}

<?php

namespace Conquest\Table\Filters;

use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use Conquest\Table\Filters\Concerns\HasDateClause;
use Conquest\Table\Filters\Concerns\HasOperator;
use Conquest\Table\Filters\Enums\DateClause;
use Conquest\Table\Filters\Enums\Operator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\Request;

class DateFilter extends PropertyFilter
{
    use HasDateClause;
    use HasOperator;

    public function setUp(): void
    {
        $this->setType('date');
        $this->setDateClause(DateClause::Date);
        $this->setOperator(Operator::Equal);
    }

    public function handle(Builder|QueryBuilder $builder): void
    {
        $this->getDateClause()
            ?->apply($builder,
                $this->getProperty(),
                $this->getOperator(),
                $this->getValue()
            );
    }

    public function getValueFromRequest(): ?Carbon
    {
        $v = Request::input($this->getName(), null);
        if (is_null($v)) {
            return null;
        }

        try {
            return Carbon::parse($v);
        } catch (InvalidFormatException $e) {
            return null;
        }
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'value' => $this->getValue()?->toDateTimeString(),
        ]);
    }
}

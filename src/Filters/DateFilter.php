<?php

namespace Conquest\Table\Filters;

use Closure;
use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Support\Facades\Request;
use Illuminate\Database\Eloquent\Builder;
use Conquest\Table\Filters\Enums\Operator;
use Conquest\Table\Filters\Enums\DateClause;
use Conquest\Table\Filters\Concerns\HasOperator;
use Conquest\Table\Filters\Concerns\HasDateClause;
use Illuminate\Database\Query\Builder as QueryBuilder;

class DateFilter extends PropertyFilter
{
    use HasDateClause;
    use HasOperator;

    public function setUp(): void
    {
        $this->setType('filter:date');
        $this->setDateClause(DateClause::DATE);
        $this->setOperator(Operator::EQUAL);
    }

    public function handle(Builder|QueryBuilder $builder): void
    {
        $this->getClause()
            ->apply($builder,
                $this->getProperty(),
                $this->getOperator(),
                $this->getValue()
            );
    }

    public function getValueFromRequest(): ?Carbon
    {
        $v = Request::input($this->getName(), null);
        if (is_null($v)) return null;

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
    }}

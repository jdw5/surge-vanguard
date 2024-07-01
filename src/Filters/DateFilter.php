<?php

namespace Conquest\Table\Filters;

use Carbon\Carbon;
use Closure;
use Conquest\Table\Filters\Concerns\HasDateClause;
use Conquest\Table\Filters\Concerns\HasOperator;
use Conquest\Table\Filters\Concerns\IsNegatable;
use Conquest\Table\Filters\Enums\DateClause;
use Conquest\Table\Filters\Enums\Operator;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

class DateFilter extends BaseFilter
{
    use HasDateClause;
    use HasOperator;
    use IsNegatable;
    // Give options

    public function __construct(
        array|string|Closure $property,
        string|Closure|null $name = null,
        string|Closure|null $label = null,
        bool|Closure|null $authorize = null,
        string|DateClause $dateClause = DateClause::DATE,
        string|Operator $operator = Operator::EQUAL,
        bool $negate = false,
    ) {
        parent::__construct($property, $name, $label, $authorize);
        $this->setDateClause($dateClause);
        $this->setOperator($operator);
        $this->setNegation($negate);
        $this->setType('filter:date');
    }

    public static function make(
        array|string|Closure $property,
        string|Closure|null $name = null,
        string|Closure|null $label = null,
        bool|Closure|null $authorize = null,
        string|DateClause $dateClause = DateClause::DATE,
        string|Operator $operator = Operator::EQUAL,
        bool $negate = false,
    ): static {
        return new static($property, $name, $label, $authorize, $dateClause, $operator, $negate);
    }

    public function apply(Builder|QueryBuilder $builder): void
    {
        $request = request();
        $value = $this->parseQueryToDate($request->query($this->getName()));

        $transformedValue = $this->transformUsing($value);
        $this->setValue($transformedValue);
        $this->setActive($this->filtering($request));

        $builder->when(
            $this->isActive() && $this->isValid($transformedValue),
            fn (Builder|QueryBuilder $builder) => $this->getClause()
                ->apply($builder,
                    $this->getProperty(),
                    $this->isNegated() ? $this->getOperator()->negate() : $this->getOperator(),
                    $this->getValue()
                )
        );
    }

    public function parseQueryToDate(mixed $value): ?Carbon
    {
        try {
            return Carbon::parse($value);
        } catch (Exception $e) {
            return null;
        }
    }
}

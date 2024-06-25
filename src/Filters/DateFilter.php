<?php

namespace Jdw5\Vanguard\Filters;

use Closure;
use Override;
use Exception;
use Carbon\Carbon;
use Jdw5\Vanguard\Filters\BaseFilter;
use Illuminate\Database\Eloquent\Builder;
use Jdw5\Vanguard\Filters\Enums\Operator;
use Jdw5\Vanguard\Filters\Enums\DateClause;
use Jdw5\Vanguard\Filters\Concerns\HasOperator;
use Jdw5\Vanguard\Filters\Concerns\IsNegatable;
use Jdw5\Vanguard\Filters\Concerns\HasDateClause;
use Illuminate\Database\Query\Builder as QueryBuilder;

class DateFilter extends BaseFilter
{
    use IsNegatable;
    use HasDateClause;
    use HasOperator;
    // Give options

    #[Override]
    public function __construct(
        array|string|Closure $property, 
        string|Closure $name = null,
        string|Closure $label = null,
        bool|Closure $authorize = null,
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
        string|Closure $name = null,
        string|Closure $label = null,
        bool|Closure $authorize = null,
        string|DateClause $dateClause = DateClause::DATE,
        string|Operator $operator = Operator::EQUAL,
        bool $negate = false,
    ): static {
        return new static($property, $name, $label, $authorize, $dateClause, $operator, $negate);
    }

    #[Override]
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
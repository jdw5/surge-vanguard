<?php

namespace Conquest\Table\Filters;

use Closure;
use Exception;
use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Support\Facades\Request;
use Illuminate\Database\Eloquent\Builder;
use Conquest\Table\Filters\Enums\Operator;
use Conquest\Table\Filters\Enums\DateClause;
use Conquest\Table\Filters\Concerns\HasOperator;
use Conquest\Table\Filters\Concerns\IsNegatable;
use Conquest\Table\Filters\Concerns\HasDateClause;
use Illuminate\Database\Query\Builder as QueryBuilder;

class DateFilter extends BaseFilter
{
    use HasDateClause;
    use HasOperator;

    public function setUp(): void
    {
        $this->setType('filter:date');
    }

    public function __construct(
        array|string|Closure $property,
        string|Closure|null $name = null,
        string|Closure|null $label = null,
        bool|Closure|null $authorize = null,
        ?Closure $validator = null,
        ?Closure $transform = null,
        string|DateClause $dateClause = DateClause::DATE,
        string|Operator $operator = Operator::EQUAL,
        array $metadata = null,
    ) {
        parent::__construct($property, $name, $label, $authorize, $validator, $transform, $metadata);
        $this->setDateClause($dateClause);
        $this->setOperator($operator);
    }

    public static function make(
        array|string|Closure $property,
        string|Closure|null $name = null,
        string|Closure|null $label = null,
        bool|Closure|null $authorize = null,
        ?Closure $validator = null,
        ?Closure $transform = null,
        string|DateClause $dateClause = DateClause::DATE,
        string|Operator $operator = Operator::EQUAL,
        array $metadata = null,
    ): static {
        return resolve(static::class, compact(
            'property',
            'name',
            'label',
            'authorize',
            'validator',
            'transform',
            'dateClause',
            'operator',
            'metadata',
        ));
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

    public function getValueFromRequest(): mixed
    {
        $v = Request::input($this->getName(), null);

        try {
            return Carbon::parse($v);
        } catch (InvalidFormatException $e) {
            return null;
        }
    }
}

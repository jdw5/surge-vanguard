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

class DateFilter extends PropertyFilter
{
    use HasDateClause;
    use HasOperator;

    public function setUp(): void
    {
        $this->setType('filter:date');
    }

    public function __construct(
        array|string|Closure $property,
        string|Closure $name = null,
        string|Closure $label = null,
        bool|Closure $authorize = null,
        ?Closure $validator = null,
        ?Closure $transform = null,
        string|DateClause $dateClause = DateClause::DATE,
        string|Operator $operator = Operator::EQUAL,
        array $metadata = null,
    ) {
        parent::__construct(
            property: $property, 
            name: $name, 
            label: $label, 
            authorize: $authorize, 
            validator: $validator, 
            transform: $transform, 
            metadata: $metadata
        );
        $this->setDateClause($dateClause);
        $this->setOperator($operator);
    }

    public static function make(
        string|Closure $property,
        string|Closure $name = null,
        string|Closure $label = null,
        bool|Closure $authorize = null,
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
        return [
            'name' => $this->getName(),
            'label' => $this->getLabel(),
            'type' => $this->getType(),
            'active' => $this->isActive(),
            'value' => $this->getValue()?->toDateTimeString(),
            'metadata' => $this->getMetadata(),
        ];
    }}

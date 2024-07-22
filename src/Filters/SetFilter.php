<?php

namespace Conquest\Table\Filters;

use Closure;
use Illuminate\Support\Facades\Request;
use Conquest\Table\Filters\Enums\Clause;
use Illuminate\Database\Eloquent\Builder;
use Conquest\Table\Filters\Enums\Operator;
use Conquest\Core\Options\Concerns\HasOptions;
use Conquest\Core\Options\Option;
use Conquest\Table\Filters\Concerns\HasClause;
use Conquest\Table\Filters\Concerns\HasOperator;
use Conquest\Table\Filters\Concerns\IsRestrictable;
use Illuminate\Database\Query\Builder as QueryBuilder;

class SetFilter extends PropertyFilter
{
    use HasOptions;
    use IsRestrictable;
    use HasClause;
    use HasOperator;

    protected bool $multiple = false;

    public function setUp(): void
    {
        $this->setType('filter:set');
    }

    public function __construct(
        array|string|Closure $property,
        string|Closure $name = null,
        string|Closure $label = null,
        bool|Closure $authorize = null,
        ?Closure $validator = null,
        ?Closure $transform = null,
        string|Clause $clause = Clause::IS,
        string|Operator $operator = Operator::EQUAL,
        bool $multiple = false,
        array $options = [],
        bool|Closure|null $restrict = null,
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
        $this->setMultiple($multiple);
        $this->setClause($clause);
        $this->setOperator($operator);
        $this->setOptions($options);
        $this->setRestricted($restrict);
    }

    public static function make(
        array|string|Closure $property,
        string|Closure $name = null,
        string|Closure $label = null,
        bool|Closure $authorize = null,
        ?Closure $validator = null,
        ?Closure $transform = null,
        string|Clause $clause = Clause::IS,
        string|Operator $operator = Operator::EQUAL,
        bool $multiple = false,
        array $options = [],
        bool|Closure $restrict = null,
        array $metadata = null,
    ): static {
        return resolve(static::class, compact(
            'property',
            'name',
            'label',
            'authorize',
            'validator',
            'transform',
            'clause',
            'operator',
            'multiple',
            'options',
            'restrict',
            'metadata'
        ));
    }

    public function apply(Builder|QueryBuilder $builder): void
    {
        $value = $this->transformUsing($this->getValueFromRequest());
        $this->setValue($value);
        $this->setActive($this->filtering($value));

        if (!$this->validateOptions($value)) return;

        $builder->when(
            $this->isActive() && $this->validateUsing($value),
            fn (Builder|QueryBuilder $builder) => $this->handle($builder),
        );
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

    public function validateOptions(): bool
    {
        if (! $this->hasOptions()) return true;

        $flag = false;
        collect($this->getOptions())->each(function (Option $option) use (&$flag) {
            $option->setActive($this->isMultiple() ? 
                in_array($option->getValue(), $this->getValue()) 
                : $option->getValue() === $this->getValue()
            );
            $flag = $flag || $option->isActive();
        });
        return $this->isRestricted() ? $flag : true;
    }

    public function getValueFromRequest(): mixed
    {
        $in = Request::input($this->getName(), null);
        if (is_null($in)) return $in;

        return $this->isMultiple() ? $this->splitToMultiple($in) : $in;

    }
    
    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'multiple' => $this->isMultiple(),
            'options' => $this->getOptions(),
        ]);
    }
    
    public function multiple(): static
    {
        $this->setMultiple(true);
        return $this;
    }
    
    public function setMultiple(bool|null $multiple): void
    {
        if (is_null($multiple)) return;
        if ($multiple && !$this->getClause()?->isMultiple()) $this->setClause(Clause::CONTAINS);
        $this->multiple = $multiple;
    }

    public function isMultiple(): bool
    {
        return $this->multiple && $this->getClause()->isMultiple();
    }

    public function splitToMultiple(?string $value): array
    {
        if (is_null($value)) return [];
        return array_map('trim', explode(',', $value));
    }
}

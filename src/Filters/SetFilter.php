<?php

declare(strict_types=1);

namespace Conquest\Table\Filters;

use Closure;
use Conquest\Core\Options\Concerns\HasOptions;
use Conquest\Core\Options\Option;
use Conquest\Table\Filters\Concerns\HasClause;
use Conquest\Table\Filters\Concerns\HasOperator;
use Conquest\Table\Filters\Concerns\IsMultiple;
use Conquest\Table\Filters\Concerns\IsRestricted;
use Conquest\Table\Filters\Enums\Clause;
use Conquest\Table\Filters\Enums\Operator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\Request;

class SetFilter extends PropertyFilter
{
    use HasClause;
    use HasOperator;
    use HasOptions;
    use IsMultiple {
        setMultiple as setMultipleTrait;
        isMultiple as isMultipleTrait;
    }
    use IsRestricted;

    public function setUp(): void
    {
        $this->setType('set');
        $this->setClause(Clause::Is);
        $this->setOperator(Operator::Equal);
    }

    public function apply(Builder|QueryBuilder $builder): void
    {
        $value = $this->applyTransform($this->getValueFromRequest());
        $this->setValue($value);
        $this->setActive($this->filtering($value));

        if (! $this->validateOptions()) {
            return;
        }

        $builder->when(
            $this->isActive() && $this->isValid($value),
            fn (Builder|QueryBuilder $builder) => $this->handle($builder),
        );
    }

    public function handle(Builder|QueryBuilder $builder): void
    {
        $this->getClause()
            ?->apply($builder,
                $this->getProperty(),
                $this->getOperator(),
                $this->getValue()
            );
    }

    public function validateOptions(): bool
    {
        if ($this->lacksOptions()) {
            return true;
        }

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
        if (is_null($in)) {
            return $in;
        }

        return $this->isMultiple() ? $this->splitToMultiple($in) : $in;

    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'multiple' => $this->isMultiple(),
            'options' => $this->getOptions(),
        ]);
    }

    public function setMultiple(bool|Closure|null $multiple): void
    {
        $this->setMultipleTrait($multiple);

        if ($this->isMultipleTrait() && ! $this->getClause()?->isMultiple()) {
            $this->setClause(Clause::Contains);
        }
    }

    public function isMultiple(): bool
    {
        return $this->isMultipleTrait() && $this->getClause()->isMultiple();
    }
}

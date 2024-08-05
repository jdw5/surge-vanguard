<?php

declare(strict_types=1);

namespace Conquest\Table\Filters;

use Closure;
use Conquest\Core\Concerns\CanTransform;
use Conquest\Core\Concerns\CanValidate;
use Conquest\Table\Filters\Concerns\HasQuery;
use Illuminate\Contracts\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder;

class QueryFilter extends BaseFilter
{
    use CanTransform;
    use CanValidate;
    use HasQuery;

    public function setUp(): void
    {
        $this->setType('query');
    }

    public function __construct(string|Closure $name, string|Closure|null $label = null, ?Closure $query = null)
    {
        parent::__construct($name, $label);
        $this->setQuery($query);
    }

    public static function make(string|Closure $name, string|Closure|null $label = null, ?Closure $query = null): static
    {
        return resolve(static::class, compact(
            'name',
            'label',
            'query',
        ));
    }

    public function apply(Builder|QueryBuilder $builder): void
    {
        $value = $this->applyTransform($this->getValueFromRequest());
        $this->setValue($value);
        $this->setActive($this->filtering($value));
        $builder->when(
            $this->isActive() && $this->isValid($value),
            fn (Builder|QueryBuilder $builder) => $this->handle($builder),
        );
    }

    public function handle(Builder|QueryBuilder $builder): void
    {
        $this->getQuery()($builder, $this->getValue());
    }
}

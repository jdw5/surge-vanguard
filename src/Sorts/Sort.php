<?php

namespace Conquest\Table\Sorts;

use Closure;
use Illuminate\Http\Request;
use Conquest\Table\Sorts\BaseSort;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Conquest\Core\Concerns\IsDefault;
use Conquest\Table\Sorts\Concerns\HasDirection;

class Sort extends BaseSort
{
    use IsDefault;

    public function setUp(): void
    {
        $this->setType('sort');
    }

    public function __construct(
        string|Closure $property, 
        string|Closure $name = null,
        string|Closure $label = null,
        bool|Closure $authorize = null,
        string $direction = null,
        bool $default = false,
        array $metadata = null,
    ) {
        parent::__construct($property, $name, $label, $authorize, $direction, $metadata);
        $this->setDefault($default);
    }

    public static function make(
        string|Closure $property, 
        string|Closure $name = null,
        string|Closure $label = null,
        bool|Closure $authorize = null,
        string $direction = null,
        bool $default = false,
        array $metadata = null,
    ): static {
        return resolve(static::class, compact(
            'property', 
            'name', 
            'label', 
            'authorize', 
            'direction', 
            'default',
            'metadata',
        ));
    }

    public function apply(Builder|QueryBuilder $builder, ?string $sortBy, ?string $orderBy): void
    {
        $this->setActive($this->sorting($sortBy, $orderBy));
        
        $builder->when(
            $this->isActive(),
            function (Builder|QueryBuilder $builder) use ($orderBy) {
                $builder->orderBy(
                    column: $builder instanceof Builder ? $builder->qualifyColumn($this->getProperty()) : $this->getProperty(),
                    direction: $this->hasDirection() ? $this->getDirection() : $orderBy,
                );
            }
        ); 
    }

    public function sorting(?string $sortBy, ?string $orderBy): bool
    {
        $sorts = !is_null($sortBy) && $sortBy === $this->getName();
        $orders = $this->hasDirection() ? true : !is_null($orderBy);
        return $sorts && $orders;
    }
}

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
        array $meta = null,
    ) {
        parent::__construct($property, $name, $label, $authorize, $direction, $meta);
        $this->setDefault($default);
    }

    public static function make(
        string|Closure $property, 
        string|Closure $name = null,
        string|Closure $label = null,
        bool|Closure $authorize = null,
        string $direction = null,
        bool $default = false,
        array $meta = null,
    ): static {
        return resolve(static::class, compact(
            'property', 
            'name', 
            'label', 
            'authorize', 
            'direction', 
            'default',
            'meta',
        ));
    }

    public function handle(Builder|QueryBuilder $builder, ?string $direction = null): void
    {
        $builder->orderBy(
            column: $builder instanceof Builder ? $builder->qualifyColumn($this->getProperty()) : $this->getProperty(),
            direction: $this->hasDirection() ? $this->getDirection() : $direction ?? config('table.sort.default_order', 'asc'),
        );
    }
    
    public function sorting(?string $sortBy, ?string $direction): bool
    {
        return $sortBy === $this->getName() && ($this->hasDirection() ? $direction === $this->getDirection() : true);
    }
}

<?php

namespace Jdw5\Vanguard\Sorts;

use Closure;
use Illuminate\Http\Request;
use Jdw5\Vanguard\Sorts\BaseSort;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Jdw5\Vanguard\Concerns\IsDefault;
use Jdw5\Vanguard\Sorts\Concerns\HasDirection;

/** 
 * Agnostic to the order field, considers only field with predefined direction 
 * This sort can be used as a default
 */
class Sort extends BaseSort
{
    use HasDirection;
    use IsDefault;

    public function __construct(
        string|Closure $property, 
        string|Closure $name = null,
        string|Closure $label = null,
        bool|Closure $authorize = null,
        string $direction = null,
        bool $default = false,
    ) {
        parent::__construct($property, $name, $label, $authorize);
        $this->setDirection($direction);
        $this->setDefault($default);
    }

    public function apply(Builder|QueryBuilder $builder): void
    {
        $request = request();
        
        $this->setActive($this->sorting($request));
        
        $builder->when(
            $this->isActive(),
            function (Builder|QueryBuilder $builder) {
                $builder->orderBy(
                    column: $builder->qualifyColumn($this->getProperty()),
                    direction: $this->getDirection(),
                );
            }
        ); 
    }

    protected function sorting(Request $request): bool
    {
        return $this->isDefault() ||
            ($request->has($this->getSortKey()) && $request->query($this->getSortKey()) === $this->getName());
    }
    
    public function jsonSerialize(): array
    {
        return array_merge(parent::jsonSerialize(), [
            'direction' => $this->getDirection(),
        ]);
    }
}
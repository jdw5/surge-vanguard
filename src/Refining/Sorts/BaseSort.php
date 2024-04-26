<?php

namespace Jdw5\Vanguard\Refining\Sorts;

use Illuminate\Http\Request;
use Jdw5\Vanguard\Refining\Refinement;
use Jdw5\Vanguard\Refining\Contracts\Sorts;
use Jdw5\Vanguard\Refining\Sorts\Concerns\HasDirection;
use Jdw5\Vanguard\Refining\Sorts\Concerns\SortConstants;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Jdw5\Vanguard\Concerns\IsDefault;

abstract class BaseSort extends Refinement implements Sorts
{
    use HasDirection;
    use IsDefault;
    
    public static function make(string $property, string $name = null): static
    {
        return resolve(static::class, compact('property', 'name'));
    }

    public function refine(Builder|QueryBuilder $builder, ?Request $request = null): void
    {
        if (is_null($request)) $request = request();
        
        $this->value($request->query(SortConstants::SORT_FIELD));

        if ($this->isActive() || (is_null($this->getValue()) && $this->isDefault())) {
            $this->apply($builder, $this->getProperty(), $this->getDirection());
        }         
    }

    public function apply(Builder|QueryBuilder $builder, string $property, ?string $direction = self::DEFAULT_DIRECTION): void
    {
        $builder->orderBy(
            column: $builder->qualifyColumn($property),
            direction: $direction,
        );
    }

    public function isActive(): bool
    {
        return $this->isActiveSort();
    }

    public function isActiveSort(): bool
    {
        return ($this->getValue() === $this->getName()) || ($this->isDefault() && \is_null($this->getValue()));
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => $this->getName(),
            'label' => $this->getLabel(),
            'metadata' => $this->getMetadata(),
            'active' => $this->isActive(),
        ];
    }
}
<?php

namespace Jdw5\Vanguard\Refining\Sorts;

use Illuminate\Http\Request;
use Jdw5\Vanguard\Refining\Refinement;
use Jdw5\Vanguard\Refining\Contracts\Sorts;
use Illuminate\Database\Eloquent\Builder;

abstract class BaseSort extends Refinement implements Sorts
{
    public const SORT_FIELD = 'sort';
    public const ORDER_FIELD = 'order';

    public static function make(string $property, ?string $alias = null): static
    {
        return resolve(static::class, compact('property', 'alias'));
    }

    public function refine(Builder $builder, ?Request $request = null): void
    {
        if (is_null($request)) $request = request();
        
        $this->value($request->query(self::SORT_FIELD));

        if ($this->getValue() !== $this->getName()) {
            return;
        }
        
        $this->apply($builder, $this->property);
    }

    public function apply(Builder $builder, string $property, ?string $direction = self::DEFAULT_DIRECTION): void
    {
        $direction ??= self::DEFAULT_DIRECTION;

        $builder->orderBy(
            column: $builder->qualifyColumn($property),
            direction: $direction,
        );
    }

    public function isActive(): bool
    {
        return $this->sortIsActive();
    }

    public function sortIsActive(): bool
    {
        return $this->getValue() === $this->getName();
    }
}
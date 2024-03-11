<?php

namespace Jdw5\Vanguard\Refining\Sorts;

use Illuminate\Http\Request;
use Jdw5\Vanguard\Refining\Refinement;
use Jdw5\Vanguard\Refining\Contracts\Sorts;
use Jdw5\Vanguard\Refining\Sorts\Concerns\HasDirection;
use Jdw5\Vanguard\Refining\Sorts\Concerns\SortConstants;
use Illuminate\Database\Eloquent\Builder;

abstract class BaseSort extends Refinement implements Sorts
{
    use HasDirection;
    use SortConstants;

    public static function make(string $property, ?string $name = null): static
    {
        return resolve(static::class, compact('property', 'name'));
    }

    public function refine(Builder $builder, ?Request $request = null): void
    {
        if (is_null($request)) $request = request();
        
        $this->value($request->query(self::SORT_FIELD));

        if (! $this->isActive()) return;
        
        $this->apply($builder, $this->getProperty(), $this->getDirection());
    }

    public function apply(Builder $builder, string $property, ?string $direction = self::DEFAULT_DIRECTION): void
    {
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
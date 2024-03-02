<?php

namespace Jdw5\SurgeVanguard\Refining\Concerns;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;

trait UsesRelations
{
    protected function applyRelationToBuilder(Builder $builder, string $property, $callback): void
    {
        if (!str_contains($property, '.')) {
            $callback($builder, $property, false);

            return;
        }

        [$relation, $property] = collect(explode('.', $property))
            ->pipe(fn (Collection $parts) => [
                $parts->except(\count($parts) - 1)->implode('.'),
                $parts->last(),
            ]);

        $method = 'whereHas';

        $builder->{$method}($relation, function (Builder $builder) use ($property, $callback) {
            if (!str_contains($property, '.')) {
                $callback($builder, $property, true);
            } else {
                $this->applyRelationConstraint($builder, $property, $callback);
            }
        });
    }
}
<?php

namespace Jdw5\Vanguard\Refining\Filters;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Jdw5\Vanguard\Refining\Filters\Concerns\HasOperator;

class SelectFilter extends Filter
{

    use HasOperator;

    protected function setUp(): void
    {
        $this->type('date');
    }

    public function refine(Builder $builder, ?Request $request = null): void
    {
        if (is_null($request)) $request = request();
        
        $this->value(explode(',', $request->query($this->getName())));

        // Then there's no need to apply the filter
        if (empty($this->getValue())) {
            return;
        }
        
        try {
            $this->apply($builder, $this->getValue(), $this->getProperty());
        } catch (\Exception $e) {
            throw new \Exception("Failed to apply filter {$this->getName()}: {$e->getMessage()}");
        }
    }

    public function apply(Builder $builder, string $property, mixed $value): void
    {
        $method = match ($this->getOperator()) {
            '!=' => 'whereNotIn',
            default => 'whereIn',
        };

        $booleanMethod = match ($this->getQueryBoolean()) {
            'or' => 'or' . str($method)->ucfirst(),
            default => $method,
        };

        $builder->{$booleanMethod}($property, $this->getOperator(), $value);
        return;
        
    }
}
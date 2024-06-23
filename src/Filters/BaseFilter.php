<?php

namespace Jdw5\Vanguard\Refining\Filters;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Jdw5\Vanguard\Refining\Refinement;
use Illuminate\Database\Eloquent\Builder;
use Jdw5\Vanguard\Refining\Contracts\Filters;
use Jdw5\Vanguard\Refining\Concerns\HasOptions;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Jdw5\Vanguard\Refining\Filters\Concerns\HasQueryBoolean;

abstract class BaseFilter extends Refinement implements Filters
{
    use HasOptions;
    use HasQueryBoolean;

    /**
     * Create a new filter instance.
     * 
     * @param mixed $property
     * @param string $name
     * @return static
     */
    public static function make(mixed $property, string $name = null): static
    {
        return resolve(static::class, compact('property', 'name'));
    }

    public function refine(Builder|QueryBuilder $builder, Request $request = null): void
    {
        if (\is_null($request)) $request = request();
        
        $this->setValue($request->query($this->getName()));
        

        if ($this->getValue() === null) {
            return;
        }
        
        if ($this->ignoresInvalidOptions() && $this->isInvalidOption($this->getValue())) return;
        
        // Find and set the option to active
        $this->updateOptionActivity($this->getValue());
        
        try {
            $this->apply($builder, $this->getProperty(), $this->getValue());
        } catch (\Exception $e) {
            // Suppress if in production
            if (App::environment('local')) {
                throw new \Exception("Failed to apply filter {$this->getName()}: {$e->getMessage()}");
            }
        }
    }

    /**
     * Convert the filter to an array representation
     * 
     * @return array
     */
    public function toArray(): array
    {
        return [
            'name' => $this->getName(),
            'label' => $this->getLabel(),
            'type' => $this->getType(),
            'metadata' => $this->getMetadata(),
            'active' => $this->isActive(),
            'value' => $this->getValue(),
            'options' => $this->hasOptions() ? $this->getOptions() : null,
        ];
    }
    /**
     * Serialize the filter for JSON.
     * 
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
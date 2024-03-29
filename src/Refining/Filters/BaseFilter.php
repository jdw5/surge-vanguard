<?php

namespace Jdw5\Vanguard\Refining\Filters;

use Illuminate\Http\Request;
use Jdw5\Vanguard\Refining\Refinement;
use Jdw5\Vanguard\Refining\Contracts\Filters;
use Jdw5\Vanguard\Refining\Concerns\HasOptions;
use Illuminate\Database\Eloquent\Builder;

abstract class BaseFilter extends Refinement implements Filters
{
    use HasOptions;

    public static function make(mixed $property, ?string $name = null): static
    {
        return resolve(static::class, compact('property', 'name'));
    }

    public function refine(Builder $builder, ?Request $request = null): void
    {
        if (is_null($request)) $request = request();
        
        $this->value($request->query($this->getName()));

        if ($this->getValue() === null) {
            return;
        }

        if ($this->isOnly() && !$this->inOptions($this->getValue())) {
            return;
        }

        // Find and set the option to active
        $this->setActiveOption($this->getValue());
        
        try {
            $this->apply($builder, $this->getProperty(), $this->getValue());
        } catch (\Exception $e) {
            throw new \Exception("Failed to apply filter {$this->getName()}: {$e->getMessage()}");
        }
    }

    public function jsonSerialize(): array
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
}
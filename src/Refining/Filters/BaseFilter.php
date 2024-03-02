<?php

namespace Jdw5\SurgeVanguard\Refining\Filters;

use Jdw5\SurgeVanguard\Primitive;
use Illuminate\Http\Request;
use Jdw5\SurgeVanguard\Concerns\HasName;
use Jdw5\SurgeVanguard\Concerns\HasLabel;
use Jdw5\SurgeVanguard\Concerns\IsHideable;
use Jdw5\SurgeVanguard\Refining\Refinement;
use Jdw5\SurgeVanguard\Concerns\HasMetadata;
use Jdw5\SurgeVanguard\Concerns\Configurable;
use Jdw5\SurgeVanguard\Refining\Contracts\Filters;
use Jdw5\SurgeVanguard\Refining\Concerns\HasDefault;
use Illuminate\Database\Eloquent\Builder;

abstract class BaseFilter extends Refinement implements Filters
{
    public static function make(string $property, ?string $alias = null): static
    {
        return resolve(static::class, compact('property', 'alias'));
    }

    public function refine(Builder $builder, ?Request $request = null): void
    {
        if (is_null($request)) $request = request();
        
        $this->value($request->query($this->alias));

        if ($this->getValue() === null) {
            return;
        }
        
        try {
            $this->apply($builder, $this->getValue(), $this->property);
        } catch (\Exception $e) {
            throw new \Exception("Failed to apply filter {$this->getName()}: {$e->getMessage()}");
        }
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => $this->getName(),
            'label' => $this->getLabel(),
            'metadata' => $this->getMetadata(),
            'hidden' => $this->isHidden(),
            'default' => $this->getDefaultValue(),
            'active' => $this->isActive(),
            'value' => $this->getValue(),
        ];
    }
}
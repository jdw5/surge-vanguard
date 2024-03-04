<?php

namespace Jdw5\Vanguard\Refining\Filters;

use Jdw5\Vanguard\Primitive;
use Illuminate\Http\Request;
use Jdw5\Vanguard\Concerns\HasName;
use Jdw5\Vanguard\Concerns\HasLabel;
use Jdw5\Vanguard\Concerns\IsHideable;
use Jdw5\Vanguard\Refining\Refinement;
use Jdw5\Vanguard\Concerns\HasMetadata;
use Jdw5\Vanguard\Concerns\Configurable;
use Jdw5\Vanguard\Refining\Contracts\Filters;
use Jdw5\Vanguard\Refining\Concerns\HasDefault;
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
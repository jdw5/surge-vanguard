<?php

namespace Conquest\Table\Refiners;

use Conquest\Core\Primitive;

class Refiners extends Primitive
{
    public function __construct(protected array $refiners = []) {}

    /**
     * Create a new refiner instance.
     *
     * @param  array  $actions
     */
    public static function make(...$refiners): static
    {
        return resolve(static::class, compact('refiners'));
    }

    /**
     * Add a refiner to the refiners.
     *
     * @param  Refinement  $refinement
     */
    public function add(Refiner $refinement): static
    {
        $this->refiners[] = $refinement;

        return $this;
    }

    /**
     * Retrieve the refinements.
     */
    public function defineRefinements(): array
    {
        return $this->refiners;
    }

    /**
     * Serialize the refiners.
     */
    public function toArray(): array
    {
        return [
            'sorts' => $this->getSorts(),
            'filters' => $this->getFilters(),
        ];
    }
}

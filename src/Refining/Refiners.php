<?php

namespace Jdw5\Vanguard\Refining;

use Jdw5\Vanguard\Primitive;
use Illuminate\Support\Collection;
use Jdw5\Vanguard\Refining\Refinement;
use Jdw5\Vanguard\Concerns\HasRefinements;

class Refiners extends Primitive
{
    use HasRefinements;
    
    public function __construct(protected array $refiners = []) { }

    /**
     * Create a new refiner instance.
     * 
     * @param array $actions
     * @return static
     */
    public static function make(array $refiners): static
    {
        return resolve(static::class, compact('refiners'));
    }

    /**
     * Add a refiner to the refiners.
     * 
     * @param Refinement $refinement
     * @return static
     */
    public function add(Refinement $refinement): static
    {
        $this->refiners[] = $refinement;
        return $this;
    }

    /**
     * Retrieve the refinements.
     * 
     * @return array
     */
    public function defineRefinements(): array
    {
        return $this->refiners;
    }    

    /**
     * Serialize the refiners.
     * 
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'sorts' => $this->getSorts()->values(),
            'filters' => $this->getFilters()->values(),
        ];
    }
}
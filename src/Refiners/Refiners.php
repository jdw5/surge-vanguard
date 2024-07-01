<?php

namespace Conquest\Table\Refiners;

use Conquest\Core\Primitive;
use Illuminate\Support\Collection;
use Conquest\Table\Refiners\Refiner;

class Refiners extends Primitive
{
    
    public function __construct(protected array $refiners = []) { }

    /**
     * Create a new refiner instance.
     * 
     * @param array $actions
     * @return static
     */
    public static function make(...$refiners): static
    {
        return resolve(static::class, compact('refiners'));
    }

    /**
     * Add a refiner to the refiners.
     * 
     * @param Refinement $refinement
     * @return static
     */
    public function add(Refiner $refinement): static
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
    public function toArray(): array
    {
        return [
            'sorts' => $this->getSorts(),
            'filters' => $this->getFilters(),
        ];
    }
}
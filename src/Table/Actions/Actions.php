<?php

namespace Jdw5\Vanguard\Table\Actions;

use Jdw5\Vanguard\Concerns\HasActions;
use Jdw5\Vanguard\Primitive;

class Actions extends Primitive
{   
    use HasActions;

    public function __construct(protected array $actions = []) { }

    /**
     * Create a new actions instance.
     * 
     * @param array $actions
     * @return static
     */
    public function make(array $actions): static
    {
        return resolve(static::class, compact('actions'));
    }

    /**
     * Retrieve the actions and filter them.
     * 
     * @return array
     */
    public function getActions(): array
    {
        return $this->cachedActions ??= collect($this->actions)
            ->filter(static fn (BaseAction $action): bool => !$action->isExcluded());
    }    

    /**
     * Serialize the actions.
     * 
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'inline' => $this->getInlineActions()->values(),
            'bulk' => $this->getBulkActions()->values(),
            'page' => $this->getPageActions()->values(),
            'default' => $this->getDefaultAction(),
        ];
    }
}
<?php

namespace Jdw5\Vanguard\Table\Actions;

use Jdw5\Vanguard\Primitive;
use Jdw5\Vanguard\Concerns\HasActions;
use Jdw5\Vanguard\Table\Actions\BaseAction;

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
    public static function make(...$actions): static
    {
        return resolve(static::class, compact('actions'));
    }

    /**
     * Add an action to the actions.
     * 
     * @param BaseAction $action
     * @return static
     */
    public function add(BaseAction $action): static
    {
        $this->actions[] = $action;
        return $this;
    }

    /**
     * Retrieve the actions.
     * 
     * @return array
     */
    public function defineActions(): array
    {
        return $this->actions;
    }

    /**
     * Retrieve the actions as an array.
     * 
     * @return array
     */
    public function toArray(): array
    {
        return [
            'inline' => $this->getInlineActions(),
            'bulk' => $this->getBulkActions(),
            'page' => $this->getPageActions(),
            'default' => $this->getDefaultAction(),
        ];
    }

    /**
     * Serialize the actions.
     * 
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
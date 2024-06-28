<?php

namespace Conquest\Table\Actions;

use Conquest\Core\Primitive;
use Conquest\Table\Actions\Concerns\HasActions;
use Conquest\Table\Actions\BaseAction;

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
    protected function add(BaseAction $action): static
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
}
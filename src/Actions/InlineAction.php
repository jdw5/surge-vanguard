<?php

namespace Conquest\Table\Actions;

use Closure;
use Conquest\Core\Concerns\HasRoute;
use Conquest\Core\Concerns\IsDefault;
use Conquest\Table\Actions\BaseAction;
use Conquest\Core\Concerns\HasHttpMethod;
use Conquest\Table\Actions\Concerns\HasAction;
use Conquest\Table\Actions\Concerns\HasConfirmation;

class InlineAction extends BaseAction
{
    use IsDefault;
    use HasRoute;
    use HasHttpMethod;
    use HasAction;
    use HasConfirmation;

    public function setUp(): void
    {
        $this->setType('action:inline');
    }

    public function __construct(
        string $label, 
        string $name = null,
        bool|Closure $authorize = null,
        string|Closure $route = null,
        string $method = null,
        Closure $action = null,
        array $metadata = [],
    ) {
        parent::__construct($label, $name, $authorize, $metadata);
        $this->setRoute($route);
        $this->setMethod($method);
        $this->setAction($action);
    }

    public static function make(
        string $label, 
        string $name = null,
        bool|Closure $authorize = null,
        string|Closure $route = null,
        string $method = null,
        Closure $action = null,
        array $metadata = [],
    ): static
    {
        return resolve(static::class, compact(
            'label', 
            'name', 
            'authorize', 
            'route',
            'method',
            'action',
            'metadata',
        ));
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'route' => $this->getResolvedRoute(),
            'method' => $this->getMethod(),
            'confirmation' => $this->getConfirmation(),
            'send' => $this->hasAction(),
        ]);
    }

    // public function forRecord($record): static
    // {
    //     if ($this->hasRoute()) $this->resolveRoute($record);
    //     return $this;
    // }
}

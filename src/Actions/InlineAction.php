<?php

namespace Conquest\Table\Actions;

use Closure;
use Conquest\Core\Concerns\HasRoute;
use Conquest\Core\Concerns\IsDefault;
use Conquest\Table\Actions\BaseAction;
use Conquest\Core\Concerns\HasHttpMethod;
use Conquest\Table\Actions\Concerns\HasAction;
use Conquest\Table\Actions\Concerns\HasHandler;

class InlineAction extends BaseAction
{
    use IsDefault;
    use HasHandler;
    use HasRoute;
    use HasHttpMethod;
    use HasAction;

    public function setUp(): void
    {
        $this->setType('action:inline');
    }

    public function __construct(
        string $label, 
        string $name = null,
        Closure|bool $authorize = null,
        string|Closure $route = null,
        string $method = null,
        Closure $handle = null,
        array $metadata = [],
    ) {
        parent::__construct($label, $name, $authorize, $metadata);
        $this->setRoute($route);
        $this->setMethod($method);
        $this->setHandler($handle);
    }

    public static function make(
        string $label, 
        string $name = null,
        bool|Closure $authorize = null,
        string|Closure $route = null,
        string $method = null,
        Closure $handle = null,
        array $metadata = [],
    ): static
    {
        return resolve(static::class, compact(
            'label', 
            'name', 
            'authorize', 
            'route',
            'method',
            'handle',
            'metadata',
        ));
    }

    public function toArray(): array
    {
        $navigable = $this->hasMethod() ? [
            'route' => $this->getResolvedRoute(),
            'method' => $this->getMethod(),
        ] : [];

        $handler = $this->hasHandler() ? [
            'handler' => $this->hasHandler(),
        ] : [];
        
        return array_merge(parent::toArray(), $navigable, $handler);
    }

    // public function forRecord($record): static
    // {
    //     if ($this->hasRoute()) $this->resolveRoute($record);
    //     return $this;
    // }
}

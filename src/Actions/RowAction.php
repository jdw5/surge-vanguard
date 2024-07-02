<?php

namespace Conquest\Table\Actions;

use Closure;
use Conquest\Core\Concerns\HasRoute;
// use Conquest\Core\Concerns\IsDefault;
use Conquest\Table\Actions\BaseAction;
use Conquest\Core\Concerns\HasHttpMethod;
use Conquest\Table\Actions\Concerns\HasHandler;

class RowAction extends BaseAction
{
    // use IsDefault;
    use HasHandler;
    use HasRoute;
    use HasHttpMethod;

    public function __construct(
        string $label, 
        string $name = null,
        Closure|bool $authorize = null,
        string|Closure $route = null,
        string $method = null,
        Closure $handle = null,
        bool $default = false,
        array $metadata = [],
    ) {
        parent::__construct($label, $name, $authorize, $metadata);
        $this->setRoute($route);
        $this->setMethod($method);
        $this->setHandler($handle);
        $this->setDefault($default);
    }

    public static function make(
        string $label, 
        string $name = null,
        Closure|bool $authorize = null,
        string|Closure $route = null,
        string $method = null,
        Closure $handle = null,
        bool $default = false,
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
            'default',
            'metadata',
        ));
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'route' => $this->getResolvedRoute(),
            'method' => $this->getMethod(),
            'handler' => $this->hasHandler(),
        ]);
    }

    public function forRecord($record): static
    {
        if ($this->hasRoute()) $this->resolveRoute($record);
        return $this;
    }
}

<?php

namespace Conquest\Table\Actions;

use Closure;
use Conquest\Core\Concerns\HasRoute;
use Conquest\Table\Actions\BaseAction;
use Conquest\Core\Concerns\HasHttpMethod;

class PageAction extends BaseAction
{
    use HasRoute;
    use HasHttpMethod;

    public function setUp(): void
    {
        $this->setType('action:page');
    }

    public function __construct(
        string $label, 
        string $name = null,
        bool|Closure $authorize = null,
        string|Closure $route = null,
        string $method = null,
        array $metadata = [],
    ) {
        parent::__construct($label, $name, $authorize, $metadata);
        $this->setRoute($route);
        $this->setMethod($method);
    }

    public static function make(
        string $label,
        string $name = null,
        bool|Closure $authorize = null,
        string|Closure $route = null,
        string $method = null,
        array $metadata = [],
    ): static
    {
        return resolve(static::class, compact(
            'label', 
            'name', 
            'authorize',
            'route',
            'method', 
            'metadata'
        ));
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'route' => $this->getResolvedRoute(),
            'method' => $this->getMethod(),
        ]);
    }
}

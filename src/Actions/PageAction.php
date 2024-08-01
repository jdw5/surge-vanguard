<?php

namespace Conquest\Table\Actions;

use Closure;
use Conquest\Core\Concerns\HasHttpMethod;
use Conquest\Core\Concerns\HasRoute;
use Conquest\Table\Table;

class PageAction extends BaseAction
{
    use HasHttpMethod;
    use HasRoute;

    public function setUp(): void
    {
        $this->setType(Table::PAGE_ACTION);
    }

    public function __construct(
        string $label,
        ?string $name = null,
        bool|Closure|null $authorize = null,
        string|Closure|null $route = null,
        ?string $method = null,
        array $meta = [],
    ) {
        parent::__construct($label, $name, $authorize, $meta);
        $this->setRoute($route);
        $this->setMethod($method);
    }

    public static function make(
        string $label,
        ?string $name = null,
        bool|Closure|null $authorize = null,
        string|Closure|null $route = null,
        ?string $method = null,
        array $meta = [],
    ): static {
        return resolve(static::class, compact(
            'label',
            'name',
            'authorize',
            'route',
            'method',
            'meta'
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

<?php

namespace Conquest\Table\Actions;

use Closure;
use Conquest\Table\Table;
use Conquest\Core\Concerns\HasRoute;
use Conquest\Core\Concerns\IsDefault;
use Conquest\Table\Actions\BaseAction;
use Conquest\Core\Concerns\HasHttpMethod;
use Conquest\Table\Actions\Concerns\HasAction;
use Conquest\Table\Actions\Concerns\Confirmation\Confirms;

class InlineAction extends BaseAction
{
    use IsDefault;
    use HasRoute;
    use HasHttpMethod;
    use HasAction;
    use Confirms;

    public function setUp(): void
    {
        $this->setType(Table::INLINE_ACTION);
    }

    public function __construct(
        string $label, 
        string $name = null,
        bool|Closure $authorize = null,
        string|Closure $route = null,
        string $method = null,
        Closure $action = null,
        string|Closure $confirmation = null,
        bool|Closure $default = null,
        array $meta = [],
    ) {
        parent::__construct($label, $name, $authorize, $meta);
        $this->setRoute($route);
        $this->setMethod($method);
        $this->setAction($action);
        $this->setConfirmation($confirmation);
        $this->setDefault($default);
    }

    public static function make(
        string $label, 
        string $name = null,
        bool|Closure $authorize = null,
        string|Closure $route = null,
        string $method = null,
        Closure $action = null,
        string|Closure $confirmation = null,
        bool|Closure $default = null,
        array $meta = [],
    ): static
    {
        return resolve(static::class, compact(
            'label', 
            'name', 
            'authorize', 
            'route',
            'method',
            'action',
            'confirmation',
            'default',
            'meta',
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

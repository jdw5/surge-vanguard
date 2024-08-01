<?php

namespace Conquest\Table\Actions;

use Closure;
use Conquest\Core\Concerns\HasHttpMethod;
use Conquest\Core\Concerns\HasRoute;
use Conquest\Core\Concerns\IsDefault;
use Conquest\Table\Actions\Concerns\Confirmation\Confirms;
use Conquest\Table\Actions\Concerns\HasAction;
use Conquest\Table\Table;

class InlineAction extends BaseAction
{
    use Confirms;
    use HasAction;
    use HasHttpMethod;
    use HasRoute;
    use IsDefault;

    public function setUp(): void
    {
        $this->setType(Table::INLINE_ACTION);
    }

    public function __construct(
        string $label,
        ?string $name = null,
        bool|Closure|null $authorize = null,
        string|Closure|null $route = null,
        ?string $method = null,
        ?Closure $action = null,
        string|Closure|null $confirmation = null,
        bool|Closure|null $default = null,
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
        ?string $name = null,
        bool|Closure|null $authorize = null,
        string|Closure|null $route = null,
        ?string $method = null,
        ?Closure $action = null,
        string|Closure|null $confirmation = null,
        bool|Closure|null $default = null,
        array $meta = [],
    ): static {
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

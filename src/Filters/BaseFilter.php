<?php

namespace Conquest\Table\Filters;

use Closure;
use Conquest\Core\Primitive;
use Conquest\Core\Concerns\HasName;
use Conquest\Core\Concerns\HasType;
use Conquest\Core\Concerns\HasLabel;
use Conquest\Core\Concerns\HasValue;
use Conquest\Core\Concerns\CanValidate;
use Conquest\Core\Concerns\HasMeta;
use Illuminate\Support\Facades\Request;
use Conquest\Core\Concerns\IsAuthorized;
use Conquest\Core\Concerns\CanTransform;
use Conquest\Core\Concerns\IsActive;
use Conquest\Table\Contracts\Filters;

abstract class BaseFilter extends Primitive implements Filters
{
    use CanTransform;
    use CanValidate;
    use HasValue;
    use IsAuthorized;
    use HasLabel;
    use HasMeta;
    use HasName;
    use HasType;
    use IsActive;

    public function __construct(
        string|Closure $name = null,
        string|Closure $label = null,
        bool|Closure $authorize = null,
        array $meta = null,
    ) {
        parent::__construct();
        $this->setName($name);
        $this->setLabel($label ?? $this->toLabel($this->getName()));
        $this->setAuthorize($authorize);
        $this->setMeta($meta);
    }

    public function getValueFromRequest(): mixed
    {
        return Request::input($this->getName(), null);
    }

    public function filtering(mixed $value): bool
    {
        return !is_null($value);
    }

    public function toArray(): array
    {
        return [
            'name' => $this->getName(),
            'label' => $this->getLabel(),
            'type' => $this->getType(),
            'active' => $this->isActive(),
            'value' => $this->getValue(),
            'meta' => $this->getMeta(),
        ];
    }
}

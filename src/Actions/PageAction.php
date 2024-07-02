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

    public static function make(
        string $label,
        string $name = null,
        Closure|bool $authorize = null,
    ): static
    {
        return resolve(static::class, compact(
            'label', 
            'name', 
            'authorize', 
        ));
    }
}

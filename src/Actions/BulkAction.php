<?php

namespace Conquest\Table\Actions;

use Closure;
use Conquest\Table\Actions\BaseAction;
use Conquest\Table\Actions\Concerns\HasAction;
use Conquest\Table\Actions\Concerns\HasChunking;

class BulkAction extends BaseAction
{
    use HasChunking;
    use HasAction;
    
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

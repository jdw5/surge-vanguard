<?php

declare(strict_types=1);

namespace Conquest\Table\Actions\Confirm;

use Closure;
use Conquest\Core\Primitive;
use Conquest\Core\Concerns\HasTitle;
use Conquest\Core\Concerns\HasDescription;
use Conquest\Core\Contracts\ProxiesHigherOrder;
use Conquest\Table\Actions\Confirm\Concerns\HasCancel;
use Conquest\Table\Actions\Confirm\Concerns\HasSubmit;
use Conquest\Table\Actions\Confirm\Concerns\HasIntent;

class Confirm extends Primitive
{
    use HasTitle;
    use HasDescription; 
    use HasCancel;
    use HasSubmit;
    use HasIntent;

    public function __construct(array $state = [])
    {
        $this->setState($state);
    }

    public static function make(string|Closure $title = null, string|Closure $description = null): static
    {
        return resolve(static::class, compact('title', 'description'));
    }

    public function state(array $state): static
    {
        $this->setState($state);
        return $this;
    }

    public function setState(array $state): void
    {
        foreach ($state as $key => $value) {
            $method = 'set' . str($key)->studly()->value();
            if (method_exists($this, $method)) {
                $this->{$method}($value);
            }
        }
    }

    public function toArray(): array
    {
        return [
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'cancel' => $this->getCancel(),
            'submit' => $this->getSubmit(),
            'intent' => $this->getIntent(),
        ];
    }
}
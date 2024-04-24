<?php

namespace Jdw5\Vanguard\Table\Actions\Concerns;

use Jdw5\Vanguard\Table\Actions\Concerns\HasRoute;
use Jdw5\Vanguard\Table\Actions\Concerns\HasMethod;

trait HasEndpoint 
{
    use HasMethod;
    use HasRoute;

    public function endpoint(string $method, ...$args): static
    {
        $this->setMethod($method);
        $this->route(...$args);
        return $this;
    }
    
    public function hasEndpoint(): bool
    {
        return $this->hasRoute();
    }

    public function resolveEndpoint(mixed $record): ?array
    {
        return $this->hasEndpoint() ? 
            [
                'method' => $this->getMethod(),
                'route' => $this->resolveRoute($record),
            ] : null;
    }
}
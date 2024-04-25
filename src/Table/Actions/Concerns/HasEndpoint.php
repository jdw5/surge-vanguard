<?php

namespace Jdw5\Vanguard\Table\Actions\Concerns;

use Jdw5\Vanguard\Table\Actions\Concerns\HasRoute;
use Jdw5\Vanguard\Table\Actions\Concerns\HasMethod;

trait HasEndpoint 
{
    use HasMethod;
    use HasRoute;

    /**
     * Define the endpoint for the action.
     * 
     * @param string|\Closure $method
     * @param \Closure|string $route
     * @param mixed $parameters Optional
     */
    public function endpoint(string|\Closure $method, ...$args): static
    {
        $this->setMethod($method);
        $this->route(...$args);
        return $this;
    }
    
    /**
     * Check if the action has an endpoint
     * 
     * @return bool
     */
    public function hasEndpoint(): bool
    {
        return $this->hasRoute();
    }

    /**
     * Resolve the endpoint for the action
     * 
     * @param mixed $record
     * @return array|null
     */
    public function resolveEndpoint($record): ?array
    {
        return $this->hasEndpoint() ? 
            [
                'method' => $this->getMethod(),
                'route' => $this->resolveRoute($record),
            ] : null;
    }

    /** 
     * Serialize the endpoint for the action where they are not dependent on records
     * 
     * @return array
     */
    public function serializeStaticEndpoint(): array
    {
        return [
            'has_endpoint' => $this->hasEndpoint(),
            'endpoint' => $this->resolveEndpoint(null),
        ];
    }
}
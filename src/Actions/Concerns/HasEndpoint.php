<?php

namespace Conquest\Table\Actions\Concerns;

trait HasEndpoint
{
    use HasMethod;
    use HasRoute;

    /**
     * Define the endpoint for the action.
     *
     * @param  \Closure|string  $route
     * @param  mixed  $parameters  Optional
     */
    public function endpoint(string|\Closure $method, ...$args): static
    {
        $this->setMethod($method);
        $this->route(...$args);

        return $this;
    }

    /**
     * Check if the action has an endpoint
     */
    public function hasEndpoint(): bool
    {
        return $this->hasRoute();
    }

    /**
     * Resolve the endpoint for the action
     *
     * @param  mixed  $record
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
     */
    public function serializeStaticEndpoint(): array
    {
        return [
            'has_endpoint' => $this->hasEndpoint(),
            'endpoint' => $this->resolveEndpoint(null),
        ];
    }
}

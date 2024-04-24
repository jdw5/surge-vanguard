<?php

namespace Jdw5\Vanguard\Table\Actions\Concerns;

use Jdw5\Vanguard\Table\Actions\Exceptions\InvalidEndpointMethod;

/**
 * Trait HasEndpoint
 * 
 * Creates an endpoint dynamically, given a set of parameters
 * 
 * @property string|Closure $endpoint
 */
trait HasRoute
{
    protected string $route;
    protected array|\Closure $parameters;
    protected bool $named = true;

    /**
     * Set the route for the action and the parameters to be used
     * 
     * @param string $route
     * @param array|\Closure $params
     * @return static
     */
    public function route(string $route, array|\Closure $params): static
    {
        $this->setRoute($route);
        $this->setParameters($params);
        return $this;
    }

    /**
     * Set the route name for the action, used for completing in parts
     * 
     * @param string $route
     * @return static
     */
    public function routeName(string $route): static
    {
        $this->setRoute($route);
        return $this;
    }

    /**
     * Set the parameters for the route (must be used with route())
     * 
     * @param array|\Closure $parameters
     * @return static
     */
    public function routeParameters(array|\Closure $parameters): static
    {
        $this->setRouteParameters($parameters);
        return $this;
    }

    /**
     * Set the route to not be named
     * 
     * @return static
     */
    public function unnamed(): static
    {
        $this->setNamed(false);
        return $this;
    }

    /**
     * Retrieve the route name
     * 
     * @return string
     */
    public function getRoute(): string
    {
        return $this->evaluate($this->route);
    }

    /**
     * Check if the action has a route
     * 
     * @return bool
     */
    public function hasRoute(): bool
    {
        return isset($this->route);
    }

    /**
     * Retrieve the parameters for the route
     * 
     * @return array|\Closure
     */
    public function getParameters(): array|\Closure
    {
        return $this->evaluate($this->parameters);
    }

    /**
     * Check if the action has parameters
     * 
     * @return bool
     */
    public function hasParameters(): bool
    {
        return isset($this->parameters);
    }

    /**
     * Set the route name for the action
     * 
     * @param string|\Closure $route
     */
    protected function setRoute(string $route): void
    {
        $this->route = $route;
    }

    /**
     * Set the parameters for the route
     * 
     * @param array|\Closure $parameters
     * @return void
     */
    protected function setRouteParameters(array|\Closure $parameters): void
    {
        $this->parameters = $parameters;
    }

    /**
     * Set whether this is a named route or not.
     * Default is true.
     * 
     * @param bool $named
     * @return void
     */
    private function setNamed(bool $named): void
    {
        $this->named = $named;
    }

    /**
     * Get whether this is a named route or not
     * 
     * @return bool
     */
    private function getNamed(): bool
    {
        return $this->named;
    }

    /**
     * Resolve the endpoint for the action given a record
     * 
     * @return string if the endpoint could be resolved
     * @return null if the endpoint could not be resolved or an error was thrown
     */
    private function resolveRoute(mixed $record): ?string
    {
        // Ensure the route is set
        if (!$this->hasRoute()) {
            return null;
        }

        if (!$this->getNamed()) {
            return $this->getRoute();
        }

        // The endpoint does not depend on the record
        if (!$this->hasParameters()) {
            return route($this->getRoute());
        }

        // If the parameters are a closure, resolve them
        $parameters = $this->getParameters();
        if ($parameters instanceof \Closure) {
            $parameters = $parameters($record);
        } 

        // Ziggy should handle the hard work here, and resolve the array, associative array or single value
        // into a valid route
        return route($this->getRoute(), $parameters);
    }  
}
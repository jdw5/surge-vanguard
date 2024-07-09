<?php

namespace Conquest\Table\Actions\Concerns;

use Conquest\Table\Actions\Exceptions\InvalidEndpointMethod;

/**
 * Trait HasEndpoint
 *
 * Set an endpoint to be accessed on the client
 *
 * @property string $method
 */
trait HasMethod
{
    protected string $method = 'post';

    /**
     * Set the method for the endpoint
     */
    public function method(string $method): static
    {
        $this->setMethod($method);

        return $this;
    }

    /**
     * Get the method for the endpoint
     */
    public function getMethod(): string
    {
        return $this->evaluate($this->method);
    }

    /**
     * Set the method for the endpoint quietly
     *
     * @param  string  $method
     *
     * @throws InvalidEndpointMethod
     */
    protected function setMethod(string|\Closure $method): void
    {
        if (\is_string($method) && ! \in_array($method, ['get', 'post', 'put', 'patch', 'delete'])) {
            throw InvalidEndpointMethod::make($method);
        }
        $this->method = $method;
    }

    /**
     * Set the method for the endpoint to get
     */
    public function get(): static
    {
        return $this->method('get');
    }

    /**
     * Set the method for the endpoint to post
     */
    public function post(): static
    {
        return $this->method('post');
    }

    /**
     * Set the method for the endpoint to put
     */
    public function put(): static
    {
        return $this->method('put');
    }

    /**
     * Set the method for the endpoint to patch
     */
    public function patch(): static
    {
        return $this->method('patch');
    }

    /**
     * Set the method for the endpoint to delete
     */
    public function delete(): static
    {
        return $this->method('delete');
    }
}

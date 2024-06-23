<?php

namespace Jdw5\Vanguard\Actions\Concerns;

use Jdw5\Vanguard\Table\Actions\Exceptions\InvalidEndpointMethod;

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
     * 
     * @param string $method
     * @return static
     */
    public function method(string $method): static
    {
        $this->setMethod($method);
        return $this;
    }

    /**
     * Get the method for the endpoint
     * 
     * @return string
     */
    public function getMethod(): string
    {
        return $this->evaluate($this->method);
    }

    /**
     * Set the method for the endpoint quietly
     * 
     * @param string $method
     * @return void
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
     * 
     * @return static
     */
    public function get(): static
    {
        return $this->method('get');
    }

    /**
     * Set the method for the endpoint to post
     * 
     * @return static
     */
    public function post(): static
    {
        return $this->method('post');
    }

    /**
     * Set the method for the endpoint to put
     * 
     * @return static
     */
    public function put(): static
    {
        return $this->method('put');
    }

    /**
     * Set the method for the endpoint to patch
     * 
     * @return static
     */
    public function patch(): static
    {
        return $this->method('patch');
    }

    /**
     * Set the method for the endpoint to delete
     * 
     * @return static
     */
    public function delete(): static
    {
        return $this->method('delete');
    }
}
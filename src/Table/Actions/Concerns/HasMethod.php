<?php

namespace Jdw5\Vanguard\Table\Actions\Concerns;

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
     * Get the method for the endpoint
     * 
     * @return string
     */
    public function getMethod(): string
    {
        return $this->evaluate($this->method);
    }

    /**
     * Set the method for the endpoint
     * 
     * @param string $method
     * @return static
     * @throws InvalidEndpointMethod
     */
    public function setMethod(string $method): static
    {
        if (!\in_array($method, ['get', 'post', 'put', 'patch', 'delete'])) {
            throw InvalidEndpointMethod::invalid($method);
        }
        $this->method = $method;
        return $this;
    }
    

    /**
     * Set the method for the endpoint to get
     * 
     * @return static
     */
    public function get(): static
    {
        return $this->setMethod('get');
    }

    /**
     * Set the method for the endpoint to post
     * 
     * @return static
     */
    public function post(): static
    {
        return $this->setMethod('post');
    }

    /**
     * Set the method for the endpoint to put
     * 
     * @return static
     */
    public function put(): static
    {
        return $this->setMethod('put');
    }

    /**
     * Set the method for the endpoint to patch
     * 
     * @return static
     */
    public function patch(): static
    {
        return $this->setMethod('patch');
    }

    /**
     * Set the method for the endpoint to delete
     * 
     * @return static
     */
    public function delete(): static
    {
        return $this->setMethod('delete');
    }
}
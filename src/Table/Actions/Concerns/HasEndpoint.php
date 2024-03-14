<?php

namespace Jdw5\Vanguard\Table\Actions\Concerns;

use Jdw5\Vanguard\Table\Actions\Exceptions\InvalidEndpointMethod;

/**
 * Trait HasEndpoint
 * 
 * Set an endpoint to be accessed on the client
 * 
 * @property string $endpoint
 * @property string $method
 */
trait HasEndpoint
{
    protected ?string $endpoint = null;
    protected string $method = 'post';

    /**
     * Set the endpoint route for the action
     * 
     * @param string $endpoint
     */
    public function endpoint(string $endpoint): static
    {
        $this->endpoint = $endpoint;
        return $this;
    }

    /**
     * Set the method for the endpoint
     * 
     * @param string $method
     * @return static
     * @throws InvalidEndpointMethod
     */
    public function method(string $method): static
    {
        if (! in_array($method, ['get', 'post', 'put', 'patch', 'delete'])) {
            throw InvalidEndpointMethod::invalid($method);
        }
        $this->method = $method;
        return $this;
    }

    /**
     * Check if the action has an endpoint
     * 
     * @return bool
     */
    public function hasEndpoint(): bool
    {
        return isset($this->endpoint);
    }

    /**
     * Get the endpoint route for the action
     * 
     * @return string
     */
    public function getEndpoint(): string
    {
        return $this->evaluate($this->endpoint);
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
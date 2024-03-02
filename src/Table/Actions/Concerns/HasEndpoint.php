<?php

namespace Jdw5\SurgeVanguard\Table\Actions\Concerns;

use Jdw5\SurgeVanguard\Table\Actions\Exceptions\InvalidEndpointMethod;

trait HasEndpoint
{
    protected ?string $endpoint = null;
    protected string $method = 'post';

    public function endpoint(string $endpoint): static
    {
        $this->endpoint = $endpoint;
        return $this;
    }

    public function method(string $method): static
    {
        if (! in_array($method, ['get', 'post', 'put', 'patch', 'delete'])) {
            throw new InvalidEndpointMethod($method);
        }
        $this->method = $method;
        return $this;
    }

    public function getEndpoint(): string
    {
        return $this->endpoint;
    }
    
    public function getMethod(): string
    {
        return $this->method;
    }

    public function get(): static
    {
        return $this->method('get');
    }

    public function post(): static
    {
        return $this->method('post');
    }

    public function put(): static
    {
        return $this->method('put');
    }

    public function patch(): static
    {
        return $this->method('patch');
    }

    public function delete(): static
    {
        return $this->method('delete');
    }

    public function hasEndpoint(): bool
    {
        return isset($this->endpoint);
    }
}
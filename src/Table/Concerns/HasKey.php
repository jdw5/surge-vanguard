<?php

namespace Jdw5\Vanguard\Table\Concerns;

use Jdw5\Vanguard\Table\Exceptions\InvalidKeyException;

trait HasKey
{
    public function getKey(): string
    {
        if (!isset($this->key) || empty($this->key)) {
            throw InvalidKeyException::invalid();
        }
        return $this->key;
    }
}
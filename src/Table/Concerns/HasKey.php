<?php

namespace Jdw5\SurgeVanguard\Table\Concerns;

use Jdw5\SurgeVanguard\Table\Exceptions\InvalidKeyException;

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
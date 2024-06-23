<?php

namespace Jdw5\Vanguard\Table\Concerns;

use Jdw5\Vanguard\Table\Exceptions\InvalidKeyException;

/**
 * Trait HasKey
 * 
 * Applies a key property onto the class
 * 
 * @property string $key
 */
trait HasKey
{
    /**
     * Retrieve the key property
     * 
     * @return string
     */
    public function getKey(): string
    {
        if (isset($this->key) && is_string($this->key)) {
            return $this->key;
        }

        if (function_exists('key')) {
            return $this->key();
        }
        
        throw InvalidKeyException::make();
    }
}
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

    /** The key field */
    protected $key;

    /**
     * Retrieve the key property
     * 
     * @return string
     */
    public function getKey(): string
    {
        if (!isset($this->key) || empty($this->key)) {
            throw InvalidKeyException::invalid();
        }
        return $this->key;
    }
}
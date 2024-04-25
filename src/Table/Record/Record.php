<?php

namespace Jdw5\Vanguard\Table\Record;

/**
 * Class Record
 * 
 * Wraps the data in a record object, to normalize accessors
 */
class Record implements \ArrayAccess
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function offsetExists($offset): bool
    {
        return isset($this->data[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->data[$offset];
    }

    public function offsetSet($offset, $value): void
    {
        $this->data[$offset] = $value;
    }

    public function offsetUnset($offset): void
    {
        unset($this->data[$offset]);
    }

    public function __get($name)
    {
        return $this->data[$name];
    }
}
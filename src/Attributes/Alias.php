<?php

namespace Jdw5\Vanguard\Attributes;

use Attribute;

class Alias extends Attribute
{
    public function __construct(public string $name) {}
}
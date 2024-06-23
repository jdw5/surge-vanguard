<?php

namespace Jdw5\Vanguard\Attributes;


#[Attribute]
class Alias {
    public function __construct(public string $name) {}
}
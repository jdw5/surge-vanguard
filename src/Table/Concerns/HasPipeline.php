<?php

namespace Jdw5\Vanguard\Table\Concerns;

trait HasPipeline
{
    abstract public function definePipeline(): void;
}
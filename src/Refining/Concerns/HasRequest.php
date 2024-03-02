<?php

namespace Jdw5\SurgeVanguard\Refining\Concerns;

use Illuminate\Http\Request;

trait HasRequest
{
    protected Request $request;

    

    protected function request(Request $request): static
    {
        $this->request = $request;

        return $this;
    }

    protected function getRequest(): Request
    {
        return $this->request;
    }
}

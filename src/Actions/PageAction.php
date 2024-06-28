<?php

namespace Conquest\Table\Actions;

use Conquest\Table\Actions\BaseAction;
use Conquest\Table\Actions\Concerns\HasEndpoint;

class PageAction extends BaseAction
{   
    public function __construct(string $name, string $route, string $method = 'GET')
    {
        $this->setName($name);
        $this->setEndpoint($route, $method);
    }
}

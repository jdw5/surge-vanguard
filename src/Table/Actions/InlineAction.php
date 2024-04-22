<?php

namespace Jdw5\Vanguard\Table\Actions;

use Jdw5\Vanguard\Concerns\IsDefault;
use Jdw5\Vanguard\Table\Actions\BaseAction;
use Jdw5\Vanguard\Table\Actions\Concerns\DependsOn;

class InlineAction extends BaseAction
{
    use IsDefault;
    use DependsOn;
}

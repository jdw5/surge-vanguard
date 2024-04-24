<?php

namespace Jdw5\Vanguard\Table\Actions;

use Jdw5\Vanguard\Concerns\IsDefault;
use Jdw5\Vanguard\Table\Actions\BaseAction;
use Jdw5\Vanguard\Table\Actions\Concerns\HasRecordDependency;

class InlineAction extends BaseAction
{
    use IsDefault;
    use HasRecordDependency;
}

<?php

namespace Conquest\Table\Actions;

use Conquest\Table\Actions\BaseAction;
use Conquest\Table\Actions\Concerns\HasChunking;
use Conquest\Table\Actions\Concerns\HasHandler;

class BulkAction extends BaseAction
{
    use HasHandler;
    use HasChunking;
}

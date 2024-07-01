<?php

namespace Conquest\Table\Actions;

use Conquest\Table\Actions\Concerns\HasChunking;
use Conquest\Table\Actions\Concerns\HasHandler;

class BulkAction extends BaseAction
{
    use HasChunking;
    use HasHandler;
}

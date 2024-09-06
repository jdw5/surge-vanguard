<?php

namespace Conquest\Table\Actions\Confirm;

use Conquest\Core\Primitive;
use Conquest\Core\Concerns\HasTitle;
use Conquest\Core\Concerns\HasDescription;
use Conquest\Table\Actions\Concerns\Confirm\HasCancelText;

class Confirm extends Primitive
{
    use HasTitle;
    use HasDescription; 
    use HasCancelText;   
}
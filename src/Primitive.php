<?php

namespace Jdw5\Vanguard;

use JsonSerializable;
use Illuminate\Support\Traits\Tappable;
use Illuminate\Support\Traits\Macroable;
use Illuminate\Support\Traits\Conditionable;

abstract class Primitive implements JsonSerializable
{
    use Conditionable;
    use Macroable;
    use Tappable;
}

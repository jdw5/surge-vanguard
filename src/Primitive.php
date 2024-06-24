<?php

namespace Jdw5\Vanguard;

use JsonSerializable;
use Illuminate\Support\Traits\Tappable;
use Illuminate\Support\Traits\Macroable;
use Illuminate\Support\Traits\Conditionable;
use Jdw5\Vanguard\Concerns\EvaluatesClosures;

abstract class Primitive implements JsonSerializable
{
    use EvaluatesClosures;
    use Conditionable;
    use Macroable;
    use Tappable;
}

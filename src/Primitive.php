<?php

namespace Jdw5\SurgeVanguard;

use JsonSerializable;
use Jdw5\SurgeVanguard\Concerns\Configurable;
use Jdw5\SurgeVanguard\Concerns\EvaluatesClosures;
use Illuminate\Support\Traits\Tappable;
use Illuminate\Support\Traits\Macroable;
use Illuminate\Support\Traits\Conditionable;

abstract class Primitive implements JsonSerializable
{
    use Configurable;
    use EvaluatesClosures;
    use Conditionable;
    use Macroable;
    use Tappable;
}

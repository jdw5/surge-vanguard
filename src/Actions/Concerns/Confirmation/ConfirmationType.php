<?php

declare(strict_types=1);

namespace Conquest\Table\Actions\Concerns\Confirmation;

enum ConfirmationType: string
{
    case Neutral = 'neutral';
    case Destructive = 'destructive';
    case Constructive = 'constructive';
    case Informative = 'informative';
}

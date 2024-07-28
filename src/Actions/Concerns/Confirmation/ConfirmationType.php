<?php
declare(strict_types=1);

namespace Conquest\Table\Actions\Concerns\Confirmation;

enum ConfirmationType: string
{
    case Destructive = 'destructive';
    case Constructive = 'constructive';
    case Informative = 'informative';
}
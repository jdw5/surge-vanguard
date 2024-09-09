<?php

namespace Conquest\Table\Actions\Enums;

enum Context: string
{
    case Inline = 'inline';
    case Bulk = 'bulk';
    case Page = 'page';
}

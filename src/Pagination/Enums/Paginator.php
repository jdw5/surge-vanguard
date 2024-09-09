<?php

namespace Conquest\Table\Pagination\Enums;

enum Paginator: string
{
    case None = 'none';
    case Page = 'page';
    case Cursor = 'cursor';
}

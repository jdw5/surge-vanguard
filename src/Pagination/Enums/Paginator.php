<?php

namespace Conquest\Table\Pagination\Enums;

enum Paginator: string
{
    case None = 'none';
    case Simple = 'paginate';
    case Cursor = 'cursor';
}

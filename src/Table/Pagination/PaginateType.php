<?php

namespace Jdw5\Vanguard\Table\Pagination;

enum PaginateType: string
{
    case NONE = 'none';
    case PAGINATE = 'paginate';
    case CURSOR = 'cursor';
    
}
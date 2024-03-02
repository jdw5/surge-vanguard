<?php

namespace Jdw5\SurgeVanguard\Table\Pagination;

enum PaginateType: string
{
    case NONE = 'none';
    case PAGINATE = 'paginate';
    // case SIMPLE = 'simple';
    case CURSOR = 'cursor';
    
}
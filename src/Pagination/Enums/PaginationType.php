<?php

namespace Conquest\Table\Pagination\Enums;

enum PaginationType: string
{
    case NONE = 'get';
    case SIMPLE = 'simple';
    case CURSOR = 'cursor';
}
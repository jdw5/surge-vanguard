<?php

namespace Conquest\Table\Pagination\Enums;

enum PaginationType: string
{
    case NONE = null;
    case SIMPLE = 'simple';
    case CURSOR = 'cursor';
}
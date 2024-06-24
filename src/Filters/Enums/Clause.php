<?php

namespace Jdw5\Vanguard\Filters\Enums;

enum FilterClause: string
{
    case EXACT = 'exact';
    case LOOSE = 'loose';
    case BEGINS_WITH = 'begins_with';
    case ENDS_WITH = 'ends_with';
}
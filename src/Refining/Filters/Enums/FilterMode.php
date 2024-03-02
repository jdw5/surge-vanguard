<?php

namespace Jdw5\SurgeVanguard\Refining\Filters\Enums;

enum FilterMode: string
{
    case EXACT = 'exact';
    case LOOSE = 'loose';
    case BEGINS_WITH = 'begins_with';
    case ENDS_WITH = 'ends_with';
}
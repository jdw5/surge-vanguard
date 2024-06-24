<?php

namespace Jdw5\Vanguard\Filters\Enums;

enum Clause: string
{
    case EQUALS = 'equals';
    case NOT_EQUALS = 'not_equals';
    case BEGINS_WITH = 'begins_with';
    case ENDS_WITH = 'ends_with';
    
}
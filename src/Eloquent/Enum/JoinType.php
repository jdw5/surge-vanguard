<?php

namespace Jdw5\Vanguard\Eloquent\Enum;

enum JoinType: string 
{
    case INNER = 'inner';
    case LEFT = 'left';
    case RIGHT = 'right';
    case CROSS = 'cross';
}

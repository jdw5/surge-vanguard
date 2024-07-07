<?php

namespace Conquest\Table\Columns\Enums;

enum Breakpoint: string
{
    case XS = 'xs';     // 400px -> Embedded device
    case SM = 'sm';     // 640px -> Phone
    case MD = 'md';     // 768px -> Tablet
    case LG = 'lg';     // 1024px -> Laptop
    case XL = 'xl';     // 1280px -> Desktop
    case XXL = 'xxl';   // 1536px -> Widescreen monitor
}
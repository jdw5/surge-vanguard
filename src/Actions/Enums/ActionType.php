<?php

namespace Conquest\Table\Actions;

use Illuminate\Http\Request;

enum ActionType: string
{
    case BULK = 'bulk';
    case ROW = 'row';
    case PAGE = 'page';

    public static function getType(Request $request): ActionType
    {
        return match ($request->input('type')) {
            'bulk' => ActionType::BULK,
            'row' => ActionType::ROW,
            default => ActionType::PAGE,
        };
    }
}

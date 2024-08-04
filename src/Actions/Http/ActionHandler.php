<?php

namespace Conques\Table\Actions\Http;

use Conquest\Table\Table;
use Illuminate\Http\Request;
use Conquest\Table\DataObjects\ActionData;
use Conquest\Table\Actions\Http\Concerns\SpecifiesTable;

class ActionHandler
{
    use SpecifiesTable;

    public function __invoke(Request $request): mixed
    {
        // $data = ActionData::from($request);

    }
}
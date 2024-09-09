<?php

namespace Conques\Table\Actions\Http;

use Conquest\Table\Table;
use Illuminate\Http\Request;
use Conquest\Table\DataObjects\ActionData;
use Conquest\Table\Actions\Http\Concerns\SpecifiesTable;

class ActionHandler
{
    use SpecifiesTable;

    public function __invoke(Request $request)
    {
        // $data = ActionData::from($request);

        // Select the type and create DTO based on type
        // An invalid type should throw exception which is rendered as a 400

        // Resolve the table and action if not already resolved from route binding

        // Apply the action

    }
}
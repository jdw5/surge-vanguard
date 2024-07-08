<?php

namespace Conquest\Table\Actions\DataTransferObjects;

use Illuminate\Http\Request;

final class BulkActionData
{
    public function __construct(
        public readonly string $name,
        public readonly string $type,
        public readonly bool $all,
        public readonly array $except,
        public readonly array $only,
    ) {
    }

    public static function from(Request $request): static
    {
        return new static(
            name: $request->string('name'),
            type: $request->string('type'),
            all: $request->boolean('all'),
            except: $request->input('except', []),
            only: $request->input('only', []),
        );
    }
}
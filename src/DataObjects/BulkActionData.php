<?php

namespace Conquest\Table\DataObjects;

use Illuminate\Http\Request;

class BulkActionData extends ActionData
{
    public function __construct(
        string $name,
        string $type,
        public readonly bool $all,
        public readonly array $except,
        public readonly array $only,
    ) {
        parent::__construct(name: $name, type: $type);
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

    public function isAll(): bool
    {
        return $this->all;
    }

    public function getExcept(): array
    {
        return $this->except;
    }

    public function getOnly(): array
    {
        return $this->only;
    }
}
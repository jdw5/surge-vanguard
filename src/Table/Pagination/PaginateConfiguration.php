<?php

namespace Jdw5\SurgeVanguard\Table\Pagination;

use JsonSerializable;

final class PaginateConfiguration implements JsonSerializable
{
    public function __construct(
        public int $perPage,
        public array $columns,
        public ?string $pageName,
        public ?int $page,
        public PaginateType $type = PaginateType::NONE,
    ) {}

    public static function make(array $data = []): static
    {
        return resolve(static::class, $data);
    }

    public static function cursor(array $data = []): static
    {
        return self::make(array_merge($data, [
            'type' => PaginateType::CURSOR
        ]));
    }

    public static function paginate(array $data = []): static
    {
        return self::make(array_merge($data, [
            'type' => PaginateType::PAGINATE
        ]));
    }

    public function jsonSerialize(): array
    {
        return [
            'perPage' => $this->perPage,
            'pageName' => $this->pageName,
            'page' => $this->page,
            'columns' => $this->columns,
            'type' => $this->type->value,
        ];
    }
}
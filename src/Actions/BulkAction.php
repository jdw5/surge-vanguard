<?php

namespace Conquest\Table\Actions;

use Closure;
use Conquest\Table\Actions\Concerns\Confirmation\Confirms;
use Conquest\Table\Actions\Concerns\HasAction;
use Conquest\Table\Actions\Concerns\HasChunking;
use Conquest\Table\Table;

class BulkAction extends BaseAction
{
    use Confirms;
    use HasAction;
    use HasChunking;

    public function setUp(): void
    {
        $this->setType(Table::BULK_ACTION);
    }

    public function __construct(
        string $label,
        ?string $name = null,
        bool|Closure|null $authorize = null,
        ?Closure $action = null,
        string|Closure|null $confirmation = null,
        ?int $chunkSize = null,
        bool $chunkById = true,
        array $meta = [],
    ) {
        parent::__construct($label, $name, $authorize, $meta);
        $this->setAction($action);
        $this->setChunkSize($chunkSize);
        $this->setChunkById($chunkById);
        $this->setConfirmation($confirmation);
    }

    public static function make(
        string $label,
        ?string $name = null,
        Closure|bool|null $authorize = null,
        ?Closure $action = null,
        string|Closure|null $confirmation = null,
        ?int $chunkSize = null,
        bool $chunkById = true,
        array $meta = [],
    ): static {
        return resolve(static::class, compact(
            'label',
            'name',
            'authorize',
            'meta',
            'action',
            'confirmation',
            'chunkSize',
            'chunkById',
        ));
    }

    public function toArray(): array
    {
        return array_merge(
            parent::toArray(),
            [
                'confirmation' => $this->getConfirmation(),
            ]
        );
    }
}

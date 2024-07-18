<?php

namespace Conquest\Table\Actions;

use Closure;
use Conquest\Table\Actions\BaseAction;
use Conquest\Table\Actions\Concerns\HasAction;
use Conquest\Table\Actions\Concerns\HasChunking;
use Conquest\Table\Actions\Concerns\HasConfirmation;

class BulkAction extends BaseAction
{
    use HasChunking;
    use HasAction;
    use HasConfirmation;

    public function setUp(): void
    {
        $this->setType('action:bulk');
    }

    public function __construct(
        string $label,
        string $name = null,
        bool|Closure $authorize = null,
        Closure $action = null,
        string|Closure $confirmation = null,
        int $chunkSize = 500,
        bool $chunkById = true,
        array $metadata = [],
    ) {
        parent::__construct($label, $name, $authorize, $metadata);
        $this->setAction($action);
        $this->setChunkSize($chunkSize);
        $this->setChunkById($chunkById);
        $this->setConfirmation($confirmation);
    }
    
    public static function make(
        string $label,
        string $name = null,
        Closure|bool $authorize = null,
        Closure $action = null,
        string|Closure $confirmation = null,
        int $chunkSize = 500,
        bool $chunkById = true,
        array $metadata = [],
    ): static
    {
        return resolve(static::class, compact(
            'label', 
            'name', 
            'authorize',
            'metadata'
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

<?php

declare(strict_types=1);

namespace Conquest\Table\Actions\Concerns\Confirm;

use Closure;

trait Confirms
{
    use HasConfirmMessage;
    use HasConfirmTitle;
    use HasConfirmType;
    use IsConfirmable;

    public function confirm(ConfirmType|string|Closure $type = null, string|Closure $title = null, string|Closure $message = null): static
    {
        $this->setConfirmType($type);
        $this->setConfirmTitle($title);
        $this->setConfirmMessage($message);

        return $this->confirmable(true);
    }

    /**
     * @return array<'confirm', array<string, string>|null>
     */
    public function toArrayConfirm(): array
    {
        if ($this->isConfirmable() || $this->hasConfirmType() || $this->hasConfirmTitle() || $this->hasConfirmMessage()) {
            return [
                'confirm' => [
                    'type' => $this->getConfirmType(),
                    'title' => $this->getConfirmTitle(),
                    'message' => $this->getConfirmMessage(),
                ],
            ];
        }
        return [
            'confirm' => null,
        ];

    }
}

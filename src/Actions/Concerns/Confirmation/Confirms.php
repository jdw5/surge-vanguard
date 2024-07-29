<?php
declare(strict_types=1);

namespace Conquest\Table\Actions\Concerns\Confirmation;

use Closure;

trait HasConfirmationType
{
    use IsConfirmable;
    use HasConfirmationType;
    use HasConfirmationMessage;
    use HasConfirmationTitle;

    public function confirm(string|ConfirmationType $type = null, string|Closure $title = null, string|Closure $message = null): static
    {
        $this->confirmationType($type);
        $this->confirmationTitle($title);
        $this->confirmationMessage($message);
        return $this->confirmable(true);
    }

    /**
     * @return array<string, array<string, string>|null>
     */
    public function toArrayConfirm(): array
    {
        if ($this->isNotConfirmable()) {
            return [
                'confirm' => null
            ];
        }

        return [
            'confirm' => [
                'type' => $this->getConfirmationTypeValue(),
                'title' => $this->getConfirmationTitle(),
                'message' => $this->getConfirmationMessage()
            ]
        ];
    }
}
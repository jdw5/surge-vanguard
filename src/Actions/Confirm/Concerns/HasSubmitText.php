<?php

declare(strict_types=1);

namespace Conquest\Table\Actions\Confirm\Concerns;

use Closure;

trait HasSubmitText
{
    protected string|Closure|null $submit = null;

    public function submit(string|Closure $submit): static
    {
        $this->setSubmitText($submit);

        return $this;
    }

    public function setSubmitText(string|Closure|null $submit): void
    {
        if (is_null($submit)) {
            return;
        }
        $this->submit = $submit;
    }

    public function hasSubmitText(): bool
    {
        return ! $this->lacksSubmitText();
    }

    public function lacksSubmitText(): bool
    {
        return is_null($this->submit);
    }

    public function getSubmitText(): string
    {
        return $this->evaluate($this->submit) ?? config('table.confirm.submit', 'Confirm');
    }
}

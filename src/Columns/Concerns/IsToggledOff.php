<?php
declare(strict_types=1);

namespace Conquest\Table\Columns\Concerns;

use Closure;
use Exception;

trait IsToggledOff
{
    // All columns are toggled on by default
    protected bool|Closure $toggledOff = false;

    /**
     * @throws Exception
     */
    public function toggledOff(bool|Closure $off = true): static
    {
        $this->setToggledOff($off);
        return $this;
    }

    /**
     * @throws Exception
     */
    public function setToggledOff(bool|Closure|null $toggledOff): void
    {
        if (is_null($toggledOff)) return;
        if ($this->isKey() && $this->evaluate($toggledOff)) {
            throw new Exception('Key columns cannot be toggled off.');
        }
        $this->toggledOff = $toggledOff;
    }

    public function isToggledOff(): bool
    {
        return $this->evaluate($this->toggledOff);
    }

    public function isNotToggledOff(): bool
    {
        return !$this->isToggledOff();
    }
}
<?php

namespace Conquest\Table\Actions\Concerns;

trait DeselectsOnEnd
{
    protected bool $deselect = true;

    public function deselectOnEnd(bool $deselectOnEnd = true): static
    {
        $this->setDeselectOnEnd($deselectOnEnd);

        return $this;
    }

    public function deselectsOnEnd(): bool
    {
        return $this->deselectOnEnd;
    }

    public function setDeselectOnEnd(bool $deselectOnEnd): void
    {
        $this->deselectOnEnd = $deselectOnEnd;
    }

    public function getDeselectOnEnd(): bool
    {
        return $this->deselecstOnEnd();
    }
}

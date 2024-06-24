<?php

namespace Jdw5\Vanguard\Columns;

use Jdw5\Vanguard\Primitive;
use Jdw5\Vanguard\Concerns\HasType;
use Jdw5\Vanguard\Enums\Breakpoint;
use Jdw5\Vanguard\Concerns\HasLabel;
use Jdw5\Vanguard\Columns\Concerns\HasBreakpoint;
use Jdw5\Vanguard\Columns\Concerns\HasScreenReaders;

abstract class BaseColumn extends Primitive
{
    use HasLabel;
    use HasScreenReaders;
    use HasBreakpoint;
    use HasType;

    public function __construct(
        string $label,
        bool $srOnly = false,
        Breakpoint|string $breakpoint = Breakpoint::NONE,
    ) {
        $this->setLabel($label);
        $this->setScreenReader($srOnly);
        $this->setBreakpoint($breakpoint);
    }

    public static function make(
        string $label,
        bool $srOnly = false,
        Breakpoint|string $breakpoint = Breakpoint::NONE,
    ): static {
        return new static($label, $srOnly, $breakpoint);
    }

    public function toArray(): array
    {
        return [
            'label' => $this->getLabel(),
            'srOnly' => $this->isScreenReaderOnly(),
            'breakpoint' => $this->getBreakpoint(),
            'type' => $this->getType(),
        ];
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }
}
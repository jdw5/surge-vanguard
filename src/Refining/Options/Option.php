<?php

namespace Jdw5\Vanguard\Refining\Options;

use Jdw5\Vanguard\Concerns\Configurable;
use Jdw5\Vanguard\Concerns\HasLabel;
use Jdw5\Vanguard\Concerns\HasMetadata;
use Jdw5\Vanguard\Concerns\IsActive;
use Jdw5\Vanguard\Concerns\IsIncludable;
use Jdw5\Vanguard\Primitive;
use Jdw5\Vanguard\Refining\Concerns\HasValue;
use BackedEnum;
use Illuminate\Database\Eloquent\Collection;
use JsonSerializable;

class Option extends Primitive implements JsonSerializable
{
    use HasLabel;
    use HasValue;
    use HasMetadata;
    use Configurable;
    use IsActive;
    use IsIncludable;

    public function __construct(mixed $value, ?string $label = null) { 
        $this->value(str($value)->replace('.', '_'));
        $this->label($label ?? str($this->getValue())->headline()->lower()->ucfirst());
    }
    

    public static function make(string $value, ?string $label = null): static
    {
        return resolve(static::class, compact('value', 'label'));
    }

    public static function collection(Collection $collection, string $valueField, ?string $labelField = null): array
    {
        return $collection->map(fn ($item) => static::make($item[$valueField], $item[$labelField]))->toArray();
    }

    public static function enum(string $enum, ?string $labelMethodName = null): array
    {
        // $enum = 
        return collect($enum::cases())->map(fn (BackedEnum $item) => static::make($item->value, !is_null($labelMethodName) ? $item->{$labelMethodName}() : null))->toArray();
    }

    public function jsonSerialize(): array
    {
        return [
            'label' => $this->getLabel(),
            'value' => $this->getValue(),
            'metadata' => $this->getMetadata(),
            'active' => $this->isActive(),
        ];
    }
}
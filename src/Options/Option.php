<?php

namespace Jdw5\Vanguard\Refining\Options;

use Jdw5\Vanguard\Concerns\Configurable;
use Jdw5\Vanguard\Concerns\HasLabel;
use Jdw5\Vanguard\Concerns\HasMetadata;
use Jdw5\Vanguard\Concerns\IsActive;
use Jdw5\Vanguard\Concerns\IsIncludable;
use Jdw5\Vanguard\Primitive;
use Jdw5\Vanguard\Refining\Concerns\HasValue;
use Illuminate\Database\Eloquent\Collection;

class Option extends Primitive
{
    use HasLabel;
    use HasValue;
    use HasMetadata;
    use Configurable;
    use IsActive;
    use IsIncludable;

    public function __construct(mixed $value, ?string $label = null) { 
        if (is_string($value)) {
            $value = str($value)->replace('.', '_');
        }
        $this->value($value);
        
        $this->label($label ?? str($this->getValue())->headline()->lower()->ucfirst());
    }
    
    /**
     * Create a new option.
     * 
     * @param mixed $value
     * @param string|null $label
     */
    public static function make(mixed $value, ?string $label = null): static
    {
        return resolve(static::class, compact('value', 'label'));
    }

    /**
     * Create an array of options from a collection.
     * 
     * @param Collection $collection
     * @param string|callable $asValue
     * @param string|callable|null $asLabel
     * @return array
     */
    public static function collection(Collection $collection, string|callable $asValue = 'value', string|callable $asLabel = null): array
    {
        return $collection->map(function ($item) use ($asValue, $asLabel) {
            $value = is_callable($asValue) ? $asValue($item) : $item[$asValue];
            $label = is_callable($asLabel) ? $asLabel($item) : (\is_null($asLabel) ? $value : $item[$asLabel]);
            return static::make($value, $label);
        })->toArray();
    }

    /**
     * Create an array of options from an array.
     * 
     * @param array $array
     * @param string|callable $asValue
     * @param string|callable|null $asLabel
     * @return array
     */
    public static function array(array $array, string|callable $asValue = 'value', string|callable $asLabel = null): array
    {
        return Option::collection(collect($array), $asValue, $asLabel);
    }

    /**
     * Create an array of options from an enum.
     * 
     * @param string $enum
     * @param string|callable|null $asLabel
     * @return array
     */
    public static function enum(string $enum, string|callable $asLabel = null): array
    {
        return collect($enum::cases())->map(function (\BackedEnum $item) use ($asLabel) {
            $label = is_callable($asLabel) ? $asLabel($item) : (\is_null($asLabel) ? $item->value : $item->{$asLabel}());
            return static::make($item->value, $label);
        })->toArray();
    }

    /**
     * Convert the object to its JSON representation.
     * 
     * @return array
     */
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
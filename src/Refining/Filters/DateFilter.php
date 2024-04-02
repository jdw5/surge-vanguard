<?php

namespace Jdw5\Vanguard\Refining\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Jdw5\Vanguard\Refining\Options\Option;
use Jdw5\Vanguard\Refining\Filters\Concerns\HasQueryBoolean;
use Jdw5\Vanguard\Refining\Filters\Concerns\HasDateOperations;
use Jdw5\Vanguard\Refining\Filters\Exceptions\InvalidDateOperator;

/**
 * Date filtering.
 */
class DateFilter extends Filter
{
    use HasQueryBoolean;
    use HasDateOperations;

    protected function setUp(): void
    {
        $this->type('date');
    }

    public function apply(Builder|QueryBuilder $builder, string $property, mixed $value): void
    {
        $value = $this->parseQuery($value);
        $builder->{$this->getDateOperator()}($property, $this->getOperator(), $value, $this->getQueryBoolean());
    }

    /**
     * Get the month number from the given value.
     * 
     * @param string $value
     * @return string
     */
    public function getMonthNumber(string $value): string
    {
        return match (strtolower($value)) {
            'jan', 'january' => '01',
            'feb', 'february' => '02',
            'mar', 'march' => '03',
            'apr', 'april' => '04',
            'may' => '05',
            'jun', 'june' => '06',
            'jul', 'july' => '07',
            'aug', 'august' => '08',
            'sep', 'september' => '09',
            'oct', 'october' => '10',
            'nov', 'november' => '11',
            'dec', 'december' => '12',
            default => null,
        };
    }

    /**
     * Parse the query value to ensure it is valid.
     * 
     * @param mixed $value
     */
    public function parseQuery(mixed $value): mixed
    {
        switch ($this->getDateOperator())
        {
            case ('whereDay'): {
                if (is_numeric($value) && $value >= 1 && $value <= 31) { 
                    return $value;
                }
                return null;
            }
            case ('whereMonth'): {
                if (is_numeric($value) && $value >= 1 && $value <= 12) { 
                    return str($value);
                }
                if (is_string($value) && $month = $this->getMonthNumber($value)) {
                    return $month;
                }

                return null;
            }
            case ('whereYear'): {
                if (is_numeric($value) && strlen($value) === 4) { 
                    return $value;
                }
                return null;
            }

            case ('whereDate'): {
                if (is_string($value) && strtotime($value)) {
                    return $value;
                }
                return null;
            }

            case ('whereTime'): {
                if (is_string($value) && strtotime($value)) {
                    return $value;
                }
                return null;
            }

            default: {
                throw InvalidDateOperator::invalid($this->getDateOperator(), $this->getName());
            }
        }
    }
    
    /**
     * Generate a list of day options to attach as filter options.
     * 
     * @return static
     */
    public function dayOptions(): static
    {
        $options = [];
        for ($i = 1; $i <= 31; $i++) {
            $options[] = Option::make($i);
        }
        return $this->options($options); 
    }

    /**
     * Generate a list of month options to attach as filter options.
     * 
     * @param bool $numeric
     * @return static
     */
    public function monthOptions(bool $numeric = false): static
    {
        $options = [];

        if ($numeric) {
            for ($i = 1; $i <= 12; $i++) {
                $options[] = Option::make($i);
            }
            return $this->options($options); 
        }

        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        foreach ($months as $month) {
            $options[] = Option::make(strtolower($month), $month);
        }
        return $this->options($options); 
    }
}
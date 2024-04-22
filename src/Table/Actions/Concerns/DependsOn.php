<?php

namespace Jdw5\Vanguard\Table\Actions\Concerns;

use Jdw5\Vanguard\Refining\Filters\Concerns\HasOperator;

/**
 * Trait IsIncludable
 * 
 * Adds the ability to provide conditions to include classes
 * 
 * @property bool|\Closure $isExcluded
 * @property bool|\Closure $isIncluded
 */
trait DependsOn
{
    use HasOperator;
    
    protected string $col;
    protected mixed $value = null;
    protected bool $dependsOn = false;
    protected bool $disabledOn = false;

    /**
     * Set a column to conditionally display this action
     * 
     * @param string $colName
     * @param string $operator
     * @param mixed $value
     * @return static
     */
    public function dependsOn(string $colName, string $operator = '=', $value): static
    {
        $this->setCol($colName);
        $this->setOperator($operator);
        $this->setValue($value);
        $this->dependent();
        return $this;
    }

    /**
     * Set a column to conditionally not display this action
     * 
     * @param string $colName
     * @param string $operator
     * @param mixed $value
     * @return static
     */
    public function disabledOn(string $colName, string $operator = '=', $value): static
    {
        $this->setCol($colName);
        $this->setOperator($operator);
        $this->setValue($value);
        $this->disabled();
        return $this;
    }

    /**
     * Check if the action is dependent
     * 
     * @return bool
     */
    public function isDependent(): bool
    {
        return $this->evaluate($this->dependsOn);
    }

    /**
     * Check if the action is disabled
     * 
     * @return bool
     */
    public function isDisabled(): bool
    {
        return $this->evaluate($this->disabledOn);
    }

    /**
     * Check if the action has a conditional
     * 
     * @return bool
     */
    public function hasConditional(): bool
    {
        return $this->isDependent() || $this->isDisabled();
    }

    /**
     * Set the column to be used for the condition
     * 
     * @param string $colName
     * @return void
     */
    private function setCol(string $colName): void
    {
        $this->col = $colName;
    }

    /**
     * Get the column to be used for the condition
     * 
     * @return string
     */
    protected function getCol(): string
    {
        return $this->evaluate($this->col);
    }
    
    /**
     * Set the value to be used for the condition
     * 
     * @param mixed $value
     * @return void
     */
    private function setValue(mixed $value): void
    {
        $this->value = $value;
    }

    /**
     * Get the value to be used for the condition
     * 
     * @return mixed
     */
    protected function getValue(): mixed
    {
        return $this->evaluate($this->value);
    }

    /**
     * Set the action to be dependent
     * 
     * @return void
     */
    protected function dependent(): void
    {
        $this->dependsOn = true;
    }

    /**
     * Set the action to be disabled
     * 
     * @return void
     */
    protected function disabled(): void
    {
        $this->disabledOn = true;
    }

    /**
     * Set the mode of the action
     * 
     * @param bool $mode
     * @return void
     */
    protected function setMode(bool $mode): void
    {
        $this->dependsOn = $mode;
    }
}

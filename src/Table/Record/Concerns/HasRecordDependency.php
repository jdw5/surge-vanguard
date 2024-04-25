<?php

namespace Jdw5\Vanguard\Table\Record\Concerns;

use Illuminate\Database\Eloquent\Model;
use Jdw5\Vanguard\Refining\Filters\Concerns\HasOperator;
use Jdw5\Vanguard\Table\Record\Concerns\WrapsRecord;
use Jdw5\Vanguard\Table\Record\Record;

trait HasRecordDependency
{
    use HasOperator;
    use WrapsRecord;
    
    /** The key or closure to evaluate against */
    protected $evaluateBy;
    /** The value to compare the key against */
    protected mixed $value;
    /** Whether the condition is when or unless */
    private bool $conditional;

    /**
     * Set the presence of an action to be dependent on condition
     * 
     * @param string|\Closure $condition
     * @param string $operator
     * @param mixed $value
     * @return static
     */
    public function whenRecord($condition, $operator = '=', $value = null): static
    {
        $this->affirmConditional();
        $this->setEvaluateBy($condition);
        if ($condition instanceof \Closure && count(func_get_args()) === 1) {
            return $this;
        }
        $this->setOperator($operator);
        $this->setValue($value);
        return $this;
    }

    /**
     * Set the presence of an action to be dependent on a negated condition
     * 
     * @param string|\Closure $condition
     * @param string $operator
     * @param mixed $value
     * @return static
     */
    public function whenNotRecord($condition, $operator = null, $value = null): static
    {
        $this->negateConditional();
        $this->setEvaluateBy($condition);
        if ($condition instanceof \Closure && count(func_get_args()) === 1) {
            return $this;
        }
        $this->setOperator($operator);
        $this->setValue($value);
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
        return isset($this->conditional);
    }

    /**
     * Get the conditional value if it exists
     * 
     * @return bool|null
     */
    public function getConditional(): ?bool
    {
        if (!$this->hasConditional()) {
            return null;
        }

        return $this->conditional;
    }

    /**
     * Check if the condition is evaluated by a closure
     * 
     * @return bool
     */
    protected function evaluatesByClosure(): bool
    {
        return $this->getEvaluateBy() instanceof \Closure;
    }

    /**
     * Set the key or closure to evaluate against
     * 
     * @param string|\Closure $evaluateBy
     * @return void
     */
    private function setEvaluateBy(string|\Closure $evaluateBy): void
    {
        $this->evaluateBy = $evaluateBy;
    }

    /**
     * Get the key or closure to evaluate against
     * 
     * @return string|\Closure
     */
    protected function getEvaluateBy(): mixed
    {
        if (!isset($this->evaluateBy)) {
            return null;
        }
        return $this->evaluate($this->evaluateBy);
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
    protected function affirmConditional(): void
    {
        $this->conditional = true;
    }

    /**
     * Set the action to be disabled
     * 
     * @return void
     */
    protected function negateConditional(): void
    {
        $this->conditional = false;
    }

    /**
     * Set the mode of the action
     * 
     * @param bool $mode
     * @return void
     */
    protected function setConditional(bool $mode): void
    {
        $this->conditional = $mode;
    }

    /**
     * Evaluate whether to show or hide the action for the given record
     * 
     * @param mixed $record
     * @return bool
     */
    public function evaluateConditional(mixed $record): bool
    {
        if (! $this->hasConditional()) {
            return true;
        }

        /** Wrap record to allow for global accessors */
        $record = $this->wrapRecord($record);

        if ($this->evaluatesByClosure()) {
            return $this->evaluateByClosure($record);
        }

        return $this->getConditional() ? $this->evaluateByKey($record) : !$this->evaluateByKey($record);
    }

    /**
     * Evaluate the condition as a closure
     * 
     * @param Record $record
     * @return bool
     */
    protected function evaluateByClosure(Record $record): bool
    {
        return $this->getEvaluateBy()($record);
    }

    /**
     * Evaluate the condition using the key, operator and value
     * 
     * @param Record $record
     * @return bool
     * @throws \InvalidArgumentException
     */
    protected function evaluateByKey(Record $record): bool
    {
        $field = $record->{$this->getEvaluateBy()};
        switch ($this->getOperator()) {
            case '=':
                return $field == $this->getValue();
            case '!=':
                return $field != $this->getValue();
            case '>':
                return $field > $this->getValue();
            case '>=':
                return $field >= $this->getValue();
            case '<':
                return $field < $this->getValue();
            case '<=':
                return $field <= $this->getValue();
            default:
                throw new \InvalidArgumentException("Unsupported operator: " . $this->getOperator());
        }
    }
}

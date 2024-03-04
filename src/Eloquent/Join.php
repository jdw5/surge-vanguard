<?php

namespace Jdw5\Vanguard\Eloquent;

use Jdw5\Vanguard\Concerns\HasModel;
use Jdw5\Vanguard\Eloquent\Concerns\HasSelects;
use Jdw5\Vanguard\Eloquent\Contracts\Joins;
use Jdw5\Vanguard\Eloquent\Enum\JoinType;
use Jdw5\Vanguard\Eloquent\Exceptions\InvalidJoinType;
use ValueError;

class Join implements Joins
{
    use HasSelects;
    use HasModel;

    protected JoinType $type = JoinType::INNER;
    protected string $operator = '=';
    protected string $foreign;
    protected string $table_id;
    protected string $model;

    public function __construct(string $table, string $foreign, string $operator, string $table_id)
    {
        $this->model = $table;
        $this->foreign = $foreign;
        $this->operator = $operator;
        $this->table_id = $table_id;
    }

    public static function make(string $table, string $foreign, string $operator, string $table_id): static
    {
        return new static($table, $foreign, $operator, $table_id);
    }

    public function getTable(): string
    {
        return $this->getModel();
    }

    public function setJoin(string|JoinType $type): static
    {
        if ($type instanceof JoinType) {
            $this->type = $type;
        } else {
            try {
                $this->type = JoinType::from($type);
            } catch (ValueError $e) {
                throw InvalidJoinType::invalid($type);
            }
        }
         
        return $this;
    }

    public function inner(): static
    {
        return $this->setJoin(JoinType::INNER);
    }

    public function left(): static
    {
        return $this->setJoin(JoinType::LEFT);
    }

    public function right(): static
    {
        return $this->setJoin(JoinType::RIGHT);
    }

    public function cross(): static
    {
        return $this->setJoin(JoinType::CROSS);
    }
}
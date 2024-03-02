<?php

namespace Jdw5\SurgeVanguard\Table\Concerns;

use Jdw5\SurgeVanguard\Table\Pagination\PaginateConfiguration;

trait HasPagination
{
    protected null|PaginateConfiguration $paginateConfig = null;

    public function paginate($perPage = 10, $columns = ['*'], $pageName = 'page', $page = null) 
    {
        $this->paginateConfig = PaginateConfiguration::paginate([
            'perPage' => $perPage,
            'columns' => $columns,
            'pageName' => $pageName,
            'page' => $page,
        ]);

        return $this;
    }

    public function cursorPaginate($perPage = 10, $columns = ['*'], $cursorName = 'cursor', $cursor = null) 
    {
        $this->paginateConfig = PaginateConfiguration::cursor([
            'perPage' => $perPage,
            'columns' => $columns,
            'pageName' => $cursorName,
            'page' => $cursor,
        ]);
        return $this;
    }

    public function isPaginated(): bool 
    {
        return ! is_null($this->paginateConfig);
    }

    public function paginateType(): string|null
    {
        return $this->paginateConfig->type->value;
    }

    public function unpackPaginateToArray(): mixed
    {
        
        return [
            'perPage' => $this->paginateConfig->perPage,
            'pageName' => $this->paginateConfig->pageName,
            'page' => $this->paginateConfig->page,
            'columns' => $this->paginateConfig->columns,
        ];
    } 


    
}
<?php

declare(strict_types=1);
namespace Conquest\Table\Tests\Pagination\Concerns\Classes;

use Conquest\Table\Table;

final class MethodTable extends Table
{
    public function perPage(): int
    {
        return 20;
    }

}
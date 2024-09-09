<?php

declare(strict_types=1);

namespace Conquest\Table\Tests\Pagination\Concerns\Classes;

use Conquest\Table\Pagination\Enums\Paginator;
use Conquest\Table\Table;

final class PropertyTable extends Table
{
    protected $showKey = 'count';

    protected $perPage = 20;

    protected $pageName = 'p';

    protected $defaultPerPage = 20;

    protected $paginator = Paginator::Cursor;
}

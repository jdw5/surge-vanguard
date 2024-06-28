<?php

namespace Workbench\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Workbench\App\Enums\Status;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'category' => Status::class,
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
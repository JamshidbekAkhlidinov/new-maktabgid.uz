<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $table = 'news';

    protected $fillable = ['tag', 'title', 'excerpt', 'body', 'source', 'published_at', 'hot'];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'hot' => 'boolean',
        ];
    }
}

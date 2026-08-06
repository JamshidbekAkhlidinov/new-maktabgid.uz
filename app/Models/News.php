<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $table = 'news';

    protected $fillable = [
        'legacy_id', 'tag', 'title', 'excerpt', 'body', 'source', 'published_at', 'hot',
        'disk', 'image_path', 'image_url',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'hot' => 'boolean',
        ];
    }
}

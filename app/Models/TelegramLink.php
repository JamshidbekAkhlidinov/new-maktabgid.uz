<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TelegramLink extends Model
{
    protected $fillable = ['phone', 'telegram_chat_id', 'telegram_username', 'linked_at'];

    protected function casts(): array
    {
        return [
            'linked_at' => 'datetime',
        ];
    }
}

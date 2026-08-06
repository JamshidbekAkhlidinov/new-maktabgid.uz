<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Xabar yuborish (POST) va polling (GET) endpointlari bir xil shaklda javob
 * qaytarishi uchun (frontend JS ikkalasini ham bitta render funksiyasi bilan
 * ko'rsatadi) — ADR-0003.
 *
 * @mixin \App\Models\Message
 */
class MessageResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'conversation_id' => $this->conversation_id,
            'sender_type' => $this->sender_type,
            'sender_user_id' => $this->sender_user_id,
            'body' => $this->body,
            'created_at' => $this->created_at->toIso8601String(),
            'mine' => $request->user()?->id === $this->sender_user_id,
        ];
    }
}

<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/** Muassasa tomonidan ota-onaga suhbatda javob yozish — Suhbatlar sahifasi (institution-cabinet). */
class MessageController extends Controller
{
    public function store(Request $request, Conversation $conversation): JsonResponse
    {
        $institution = $request->user()->institution()->firstOrFail();

        abort_unless($conversation->institution_id === $institution->id, 403);

        $data = $request->validate([
            'body' => ['required', 'string', 'max:4000'],
        ]);

        $message = $conversation->messages()->create([
            'sender_type' => 'institution',
            'sender_user_id' => $request->user()->id,
            'body' => $data['body'],
        ]);

        $conversation->update(['last_message_at' => $message->created_at]);

        return response()->json(['message' => $message], 201);
    }
}

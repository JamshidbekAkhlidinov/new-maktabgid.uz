<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Ota-ona tomonidan muassasaga yozish — Suhbatlar sahifasi (parent-cabinet).
 * Institution\MessageController bilan bir xil andoza, faqat teskari tomon.
 */
class ParentMessageController extends Controller
{
    /** Muassasa profilidagi "Suhbat boshlash" tugmasi — mavjud suhbat bo'lsa o'shani qaytaradi. */
    public function start(Request $request): JsonResponse
    {
        $data = $request->validate([
            'institution_id' => ['required', 'integer', 'exists:institutions,id'],
        ]);

        $conversation = Conversation::firstOrCreate([
            'parent_user_id' => $request->user()->id,
            'institution_id' => $data['institution_id'],
        ], [
            'last_message_at' => now(),
        ]);

        return response()->json(['conversation' => $conversation], 201);
    }

    public function store(Request $request, Conversation $conversation): JsonResponse
    {
        abort_unless($conversation->parent_user_id === $request->user()->id, 403);

        $data = $request->validate([
            'body' => ['required', 'string', 'max:4000'],
        ]);

        $message = $conversation->messages()->create([
            'sender_type' => 'parent',
            'sender_user_id' => $request->user()->id,
            'body' => $data['body'],
        ]);

        $conversation->update(['last_message_at' => $message->created_at]);

        return response()->json(['message' => $message], 201);
    }
}

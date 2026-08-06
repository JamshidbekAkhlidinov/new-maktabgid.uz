<?php

namespace App\Http\Controllers;

use App\Http\Resources\MessageResource;
use App\Models\Conversation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Ota-ona tomonidan muassasaga yozish — Suhbatlar sahifasi (parent-cabinet).
 * Institution\MessageController/TeacherMessageController bilan bir xil andoza,
 * faqat teskari tomon. Suhbatni boshlash (start) endi rol-agnostik
 * ConversationController@start'da (ADR-0003 — parent va teacher bitta tugma/
 * endpoint orqali suhbat ochadi).
 */
class ParentMessageController extends Controller
{
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

        return response()->json(['message' => new MessageResource($message)], 201);
    }
}

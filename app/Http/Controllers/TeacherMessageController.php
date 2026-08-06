<?php

namespace App\Http\Controllers;

use App\Http\Resources\MessageResource;
use App\Models\Conversation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Ustoz tomonidan muassasaga yozish — Suhbatlar sahifasi (teacher-cabinet).
 * ParentMessageController/Institution\MessageController bilan bir xil andoza (ADR-0003).
 */
class TeacherMessageController extends Controller
{
    public function store(Request $request, Conversation $conversation): JsonResponse
    {
        abort_unless($conversation->teacher_user_id === $request->user()->id, 403);

        $data = $request->validate([
            'body' => ['required', 'string', 'max:4000'],
        ]);

        $message = $conversation->messages()->create([
            'sender_type' => 'teacher',
            'sender_user_id' => $request->user()->id,
            'body' => $data['body'],
        ]);

        $conversation->update(['last_message_at' => $message->created_at]);

        return response()->json(['message' => new MessageResource($message)], 201);
    }
}

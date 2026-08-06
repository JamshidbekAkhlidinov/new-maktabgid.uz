<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use App\Http\Resources\MessageResource;
use App\Models\Conversation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Muassasa tomonidan ota-ona/ustozga suhbatda javob yozish — Suhbatlar sahifasi
 * (institution-cabinet). Suhbat kim bilan (parent yoki teacher) ekanidan qat'i
 * nazar bir xil ishlaydi — faqat institution_id egaligi tekshiriladi (ADR-0003).
 */
class MessageController extends Controller
{
    public function store(Request $request, Conversation $conversation): JsonResponse
    {
        $institution = $this->activeInstitutionOrFail($request);

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

        return response()->json(['message' => new MessageResource($message)], 201);
    }
}

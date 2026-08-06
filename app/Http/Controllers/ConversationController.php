<?php

namespace App\Http\Controllers;

use App\Http\Resources\MessageResource;
use App\Models\Conversation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Suhbatni boshlash (rol-agnostik — maktab profilidagi "Suhbat boshlash" tugmasi
 * parent yoki teacher, ikkalasi uchun ham bitta endpointga murojaat qiladi) va
 * xabarlarni "polling" bilan olish (ADR-0003 — Reverb/WebSocket emas, frontend
 * har 3 sekunda shu GET endpointni so'raydi).
 */
class ConversationController extends Controller
{
    /** Maktab profili sidebar'idagi "Suhbat boshlash" — mavjud suhbat bo'lsa o'shani qaytaradi. */
    public function start(Request $request): JsonResponse
    {
        $user = $request->user();
        abort_unless($user && ($user->isParent() || $user->isTeacher()), 403);

        $data = $request->validate([
            'institution_id' => ['required', 'integer', 'exists:institutions,id'],
        ]);

        $attributes = $user->isParent()
            ? ['parent_user_id' => $user->id, 'institution_id' => $data['institution_id']]
            : ['teacher_user_id' => $user->id, 'institution_id' => $data['institution_id']];

        $conversation = Conversation::firstOrCreate($attributes, [
            'last_message_at' => now(),
        ]);

        return response()->json([
            'conversation' => $conversation,
            'redirect' => $user->isParent()
                ? route('cabinet.conversations', ['c' => $conversation->id])
                : route('teacher.cabinet.conversations', ['c' => $conversation->id]),
        ], 201);
    }

    /**
     * Polling endpointi — `after_id` berilsa faqat shundan keyingi yangi xabarlarni
     * qaytaradi (frontend har 3 sekunda shuni so'raydi), berilmasa oxirgi 50 tasini.
     * Chaqirilganda qarshi tomondan kelgan o'qilmagan xabarlar "o'qilgan" deb belgilanadi.
     */
    public function messages(Request $request, Conversation $conversation): JsonResponse
    {
        $this->authorize('view', $conversation);

        $user = $request->user();
        $afterId = (int) $request->query('after_id', 0);

        if ($afterId > 0) {
            $messages = $conversation->messages()->where('id', '>', $afterId)->oldest()->get();
        } else {
            $messages = $conversation->messages()->latest()->limit(50)->get()->sortBy('id')->values();
        }

        // "Men"ning bu suhbatdagi rolim (parent/teacher/institution) — shundan boshqa
        // sender_type'dagi o'qilmagan xabarlar shu chaqiruvda o'qilgan deb belgilanadi.
        $viewerSenderType = match (true) {
            $conversation->parent_user_id === $user->id => 'parent',
            $conversation->teacher_user_id === $user->id => 'teacher',
            default => 'institution',
        };

        $conversation->messages()
            ->where('sender_type', '!=', $viewerSenderType)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json([
            'messages' => MessageResource::collection($messages),
        ]);
    }
}

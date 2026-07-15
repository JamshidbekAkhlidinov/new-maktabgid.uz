<?php

namespace App\Http\Controllers\Forum;

use App\Http\Controllers\Controller;
use App\Http\Requests\Forum\ReplyStoreRequest;
use App\Models\ForumThread;
use Illuminate\Http\JsonResponse;

/** Forum — mavzuga javob yozish (backend.md §6: `POST /ajax/forum/threads/{id}/replies`). */
class ReplyController extends Controller
{
    public function store(ReplyStoreRequest $request, ForumThread $thread): JsonResponse
    {
        $data = $request->validated();

        $reply = $thread->replies()->create([
            'user_id' => $request->user()->id,
            'body' => $data['body'],
            'like_count' => 0,
        ]);

        return response()->json(['reply' => $reply], 201);
    }
}

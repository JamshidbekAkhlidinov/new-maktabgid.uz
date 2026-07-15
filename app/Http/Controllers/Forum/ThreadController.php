<?php

namespace App\Http\Controllers\Forum;

use App\Http\Controllers\Controller;
use App\Http\Requests\Forum\ThreadStoreRequest;
use App\Models\ForumThread;
use Illuminate\Http\JsonResponse;

/**
 * Forum — yangi mavzu ochish (backend.md §6: `POST /ajax/forum/threads`).
 * O'qish tomoni (MaktabgidData::forumThreads/forumThread) allaqachon real edi,
 * yozish yo'li yo'q edi — ADR-0002, Faza 2.
 */
class ThreadController extends Controller
{
    public function store(ThreadStoreRequest $request): JsonResponse
    {
        $data = $request->validated();

        $thread = ForumThread::create([
            'category' => $data['category'],
            'title' => $data['title'],
            'body' => $data['body'],
            'user_id' => $request->user()->id,
            'view_count' => 0,
            'like_count' => 0,
        ]);

        return response()->json(['thread' => $thread], 201);
    }
}

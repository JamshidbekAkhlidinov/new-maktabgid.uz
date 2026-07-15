<?php

namespace App\Http\Controllers\Forum;

use App\Http\Controllers\Controller;
use App\Models\ForumLike;
use App\Models\ForumReply;
use App\Models\ForumThread;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Forum — mavzu/javobni layk qilish (toggle). `like_count` ustuni
 * `ForumLikeObserver` orqali avtomatik yangilanadi (ReviewObserver bilan bir
 * xil andoza — ADR-0002).
 */
class LikeController extends Controller
{
    public function thread(Request $request, ForumThread $thread): JsonResponse
    {
        return $this->toggle($request, $thread);
    }

    public function reply(Request $request, ForumReply $reply): JsonResponse
    {
        return $this->toggle($request, $reply);
    }

    private function toggle(Request $request, ForumThread|ForumReply $likeable): JsonResponse
    {
        $userId = $request->user()->id;

        $existing = $likeable->likes()->where('user_id', $userId)->first();

        if ($existing) {
            $existing->delete();

            return response()->json(['liked' => false, 'likes' => $likeable->refresh()->like_count]);
        }

        ForumLike::create([
            'user_id' => $userId,
            'likeable_id' => $likeable->id,
            'likeable_type' => $likeable::class,
        ]);

        return response()->json(['liked' => true, 'likes' => $likeable->refresh()->like_count]);
    }
}

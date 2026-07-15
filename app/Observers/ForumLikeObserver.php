<?php

namespace App\Observers;

use App\Models\ForumLike;

/**
 * ForumLike yaratilganda/o'chirilganda tegishli mavzu/javobning `like_count`
 * ustuni real sanoq bilan qayta hisoblanadi (ReviewObserver bilan bir xil
 * andoza — ADR-0002, Faza 2).
 */
class ForumLikeObserver
{
    public function created(ForumLike $like): void
    {
        $this->recalculate($like);
    }

    public function deleted(ForumLike $like): void
    {
        $this->recalculate($like);
    }

    private function recalculate(ForumLike $like): void
    {
        $likeable = $like->likeable;

        if (! $likeable) {
            return;
        }

        $likeable->forceFill([
            'like_count' => $likeable->likes()->count(),
        ])->save();
    }
}

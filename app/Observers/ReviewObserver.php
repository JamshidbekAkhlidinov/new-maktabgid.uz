<?php

namespace App\Observers;

use App\Models\Review;

/**
 * Sharh yaratilganda/yangilanganda/o'chirilganda tegishli muassasaning
 * `rating`/`review_count` ustunlari real `reviews` jadvalidan qayta hisoblanadi
 * (backend.md §3'da rejalashtirilgan edi — "saqlanganda institutions.rating/
 * review_count qayta hisoblanadi (model observer)" — shu observer o'shani bajaradi).
 *
 * Diqqat: bu ustunlar Admin\InstitutionController orqali ham qo'lda tahrirlanishi
 * mumkin (masalan muassasa DB'ga sharhlarsiz, boshlang'ich reyting bilan qo'shilganda) —
 * lekin har bir Review CRUD amalidan so'ng haqiqiy o'rtacha bilan qayta yoziladi.
 */
class ReviewObserver
{
    public function created(Review $review): void
    {
        $this->recalculate($review);
    }

    public function updated(Review $review): void
    {
        $this->recalculate($review);
    }

    public function deleted(Review $review): void
    {
        $this->recalculate($review);
    }

    private function recalculate(Review $review): void
    {
        $institution = $review->institution;

        if (! $institution) {
            return;
        }

        $stats = $institution->reviews()
            ->selectRaw('COUNT(*) as cnt, AVG(rating) as avg_rating')
            ->first();

        $institution->forceFill([
            'review_count' => (int) $stats->cnt,
            'rating' => $stats->cnt > 0 ? round((float) $stats->avg_rating, 1) : 0,
        ])->save();
    }
}

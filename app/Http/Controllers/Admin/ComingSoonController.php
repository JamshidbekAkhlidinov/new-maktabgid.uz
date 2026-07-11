<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

/**
 * Dizayn maketida (admin skrinshot) ko'rsatilgan, lekin hali real
 * funksionalligi yo'q sidebar bo'limlari (Guruh, Teglar, Billing,
 * Sozlamalar) uchun umumiy "tez orada" sahifasi.
 *
 * Diqqat: bu yerda hech qanday DB jadvali yo'q — sof vizual joy egallovchi.
 * Kelajakda haqiqiy modul qo'shilsa, mos route shu controller'dan olib
 * tashlanib, o'z controller'iga ko'chiriladi.
 */
class ComingSoonController extends Controller
{
    private const PAGES = [
        'group' => [
            'title' => 'Guruh',
            'icon' => 'users',
            'description' => 'Foydalanuvchi guruhlari va ruxsatlar to\'plamlarini boshqarish tez orada qo\'shiladi.',
        ],
        'tags' => [
            'title' => 'Teglar',
            'icon' => 'hash',
            'description' => 'Kontentni teglash va teglar bo\'yicha filtrlash tez orada qo\'shiladi.',
        ],
        'subscriptions' => [
            'title' => 'Obunalar',
            'icon' => 'credit-card',
            'description' => 'Muassasa obunalarini boshqarish tez orada qo\'shiladi.',
        ],
        'tariffs' => [
            'title' => 'Tariflar',
            'icon' => 'tag',
            'description' => 'Tarif rejalarini yaratish va sozlash tez orada qo\'shiladi.',
        ],
        'billing-settings' => [
            'title' => 'Billing sozlamalari',
            'icon' => 'cog',
            'description' => 'To\'lov provayderlari va billing sozlamalari tez orada qo\'shiladi.',
        ],
        'settings' => [
            'title' => 'Sozlamalar',
            'icon' => 'cog',
            'description' => 'Tizim darajasidagi umumiy sozlamalar tez orada qo\'shiladi.',
        ],
    ];

    public function show(string $page): View
    {
        abort_unless(isset(self::PAGES[$page]), 404);

        return view('admin.coming-soon', self::PAGES[$page]);
    }
}

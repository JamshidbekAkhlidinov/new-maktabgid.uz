<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Article;
use App\Models\Institution;
use App\Models\Review;
use App\Models\Specialization;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

class DashboardController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:dashboard.view'),
        ];
    }

    public function index(): View
    {
        // "Kutilmoqda" — hozircha muassasalarda alohida tasdiqlash statusi yo'q,
        // shu sababli "accepting=false" (qabul yopiq) holati vizual maketdagi
        // "kutilmoqda" belgisi sifatida ishlatiladi (sidebar badge bilan bir xil).
        $institutionsPending = Institution::where('accepting', false)->count();
        $recentReviews = Review::where('created_at', '>=', now()->subDays(7))->count();
        $publishedArticles = Article::where('published_at', '<=', now())->count();
        $pendingApplications = Application::where('status', 'pending')->count();

        $tiles = [
            [
                'label' => 'Muassasalar',
                'value' => Institution::count(),
                'icon' => 'building',
                'iconBg' => 'bg-rose-50',
                'iconColor' => 'text-rose-600',
                'badge' => $institutionsPending > 0 ? "{$institutionsPending} kutilmoqda" : null,
                'badgeColor' => 'bg-amber-50 text-amber-700',
                'href' => route('admin.institutions.index'),
            ],
            [
                'label' => 'Foydalanuvchilar',
                'value' => User::count(),
                'icon' => 'user',
                'iconBg' => 'bg-violet-50',
                'iconColor' => 'text-violet-600',
                'href' => route('admin.users.index'),
            ],
            [
                'label' => 'Sharhlar',
                'value' => Review::count(),
                'icon' => 'chat',
                'iconBg' => 'bg-rose-50',
                'iconColor' => 'text-rose-600',
                'badge' => $recentReviews > 0 ? "{$recentReviews} yangi" : null,
                'badgeColor' => 'bg-rose-50 text-rose-700',
                'href' => route('admin.reviews.index'),
            ],
            [
                'label' => 'Vakansiyalar',
                'value' => Vacancy::count(),
                'icon' => 'briefcase',
                'iconBg' => 'bg-blue-50',
                'iconColor' => 'text-blue-600',
                'href' => route('admin.vacancies.index'),
            ],
            [
                'label' => 'Blog postlar',
                'value' => Article::count(),
                'icon' => 'newspaper',
                'iconBg' => 'bg-emerald-50',
                'iconColor' => 'text-emerald-600',
                'badge' => "{$publishedArticles} nashr",
                'badgeColor' => 'bg-emerald-50 text-emerald-700',
                'href' => route('admin.articles.index'),
            ],
            [
                'label' => 'Arizalar',
                'value' => Application::count(),
                'icon' => 'clipboard',
                'iconBg' => 'bg-slate-100',
                'iconColor' => 'text-slate-600',
                'badge' => $pendingApplications > 0 ? "{$pendingApplications} kutilmoqda" : null,
                'badgeColor' => 'bg-amber-50 text-amber-700',
                'href' => route('admin.applications.index'),
            ],
            [
                'label' => 'Kategoriyalar',
                'value' => Specialization::count(),
                'icon' => 'tag',
                'iconBg' => 'bg-pink-50',
                'iconColor' => 'text-pink-600',
                'manageHref' => route('admin.specializations.index'),
            ],
            [
                'label' => 'Teglar',
                'value' => 0,
                'icon' => 'hash',
                'iconBg' => 'bg-teal-50',
                'iconColor' => 'text-teal-600',
                'manageHref' => route('admin.tags.index'),
            ],
        ];

        $latestInstitutions = Institution::with('district')->latest()->limit(6)->get();
        $latestArticles = Article::latest('published_at')->limit(4)->get();

        return view('admin.dashboard', [
            'tiles' => $tiles,
            'latestInstitutions' => $latestInstitutions,
            'latestArticles' => $latestArticles,
            'institutionsPending' => $institutionsPending,
            'recentReviews' => $recentReviews,
        ]);
    }
}

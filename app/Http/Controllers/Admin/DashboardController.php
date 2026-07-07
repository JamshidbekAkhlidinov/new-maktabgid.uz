<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Institution;
use App\Models\News;
use App\Models\Resume;
use App\Models\Review;
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
        $stats = [
            'users' => User::count(),
            'institutions' => Institution::count(),
            'vacancies' => Vacancy::count(),
            'applications_pending' => Application::where('status', 'pending')->count(),
            'reviews' => Review::count(),
            'resumes' => Resume::count(),
            'news' => News::count(),
        ];

        $latestApplications = Application::with('institution')->latest()->limit(6)->get();
        $latestInstitutions = Institution::latest()->limit(6)->get();

        return view('admin.dashboard', compact('stats', 'latestApplications', 'latestInstitutions'));
    }
}

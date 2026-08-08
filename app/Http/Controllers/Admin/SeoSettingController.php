<?php

namespace App\Http\Controllers\Admin;

use App\Enums\SettingInputType;
use App\Enums\SettingKey;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

/**
 * Sayt darajasidagi sozlamalar (SEO, JS kodlar va h.k.) — forma
 * App\Enums\SettingKey ro'yxati bo'yicha avtomatik hosil qilinadi,
 * shuning uchun yangi sozlama qo'shishda bu controllerni o'zgartirish
 * shart emas (faqat enumga case qo'shiladi).
 */
class SeoSettingController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:seo-settings.view', only: ['edit']),
            new Middleware('permission:seo-settings.update', only: ['update']),
        ];
    }

    public function edit(): View
    {
        $keys = SettingKey::cases();
        $values = Setting::values();

        return view('admin.settings.edit', compact('keys', 'values'));
    }

    public function update(Request $request): RedirectResponse
    {
        $rules = [];
        foreach (SettingKey::cases() as $key) {
            $rules[$key->value] = $key->inputType() === SettingInputType::Image
                ? ['nullable', 'image', 'max:4096']
                : ['nullable', 'string', 'max:'.$key->maxLength()];
        }
        $data = $request->validate($rules);

        foreach (SettingKey::cases() as $key) {
            if ($key->inputType() === SettingInputType::Image) {
                // Yangi fayl yuklanmasa mavjud qiymat (URL) o'zgarishsiz qoladi.
                if ($request->hasFile($key->value)) {
                    $disk = config('filesystems.media_disk', 'public');
                    $path = $request->file($key->value)->store('settings', $disk);
                    Setting::set($key, Storage::disk($disk)->url($path));
                }

                continue;
            }

            $value = $data[$key->value] ?? null;
            Setting::set($key, blank($value) ? null : $value);
        }

        return redirect()->route('admin.settings.index')->with('status', 'Sozlamalar saqlandi.');
    }
}

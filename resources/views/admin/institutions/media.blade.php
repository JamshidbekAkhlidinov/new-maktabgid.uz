@extends('admin.layout')

@section('title', 'Galereya va videolar')

@section('content')
    <x-admin.page-header title="Galereya va videolar: {{ $institution->name }}" />

    <a href="{{ route('admin.institutions.edit', $institution) }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-500 hover:text-slate-800 mb-5">
        <x-admin.icon name="arrow-left" class="w-4 h-4" /> Tashkilot tahririga qaytish
    </a>

    <x-admin.card>
        <h3 class="text-base font-semibold text-slate-900 mb-1">Galereya</h3>
        <p class="text-sm text-slate-500 mb-4">JPG, PNG, WebP — maks 5 MB. /{{ $institution->slug }} sahifasidagi asosiy rasm va galereya shu ro'yxatdan.</p>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3 mb-5">
            @foreach ($galleryMedia as $m)
                <div class="relative aspect-square rounded-lg overflow-hidden border border-slate-200 bg-slate-50 bg-cover bg-center" style="background-image:url('{{ $m->url }}')">
                    <form method="POST" action="{{ route('admin.institutions.media.destroy', [$institution, $m]) }}" onsubmit="return confirm('Rostdan ham o\'chirmoqchimisiz?')" class="absolute top-1.5 right-1.5">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="flex items-center justify-center w-7 h-7 rounded-full bg-black/60 text-white hover:bg-rose-600 transition" title="O'chirish">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6L6 18" /></svg>
                        </button>
                    </form>
                    @if ($m->caption)
                        <span class="absolute bottom-0 inset-x-0 bg-black/50 text-white text-[11px] px-2 py-1 truncate">{{ $m->caption }}</span>
                    @endif
                </div>
            @endforeach

            @if ($galleryMedia->isEmpty())
                <p class="col-span-full text-sm text-slate-500">Hali rasm yuklanmagan.</p>
            @endif
        </div>

        <form method="POST" action="{{ route('admin.institutions.media.store', $institution) }}" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end border-t border-slate-100 pt-4">
            @csrf
            <input type="hidden" name="type" value="gallery" />
            <x-admin.image-upload name="file" label="Rasm qo'shish" />
            <x-admin.input name="caption" label="Izoh" placeholder="Ixtiyoriy" />
            <button type="submit" class="rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-5 py-2.5 h-fit">Yuklash</button>
        </form>
    </x-admin.card>

    <x-admin.card class="mt-6">
        <h3 class="text-base font-semibold text-slate-900 mb-1">Videolar</h3>
        <p class="text-sm text-slate-500 mb-4">Haqiqiy video fayl (mp4/webm/mov, maks 100MB) yoki tashqi havola (YouTube/Vimeo).</p>

        <div class="divide-y divide-slate-100 mb-5">
            @forelse ($videoMedia as $v)
                <div class="flex items-center gap-3 py-3">
                    <span class="flex items-center justify-center w-9 h-9 rounded-lg bg-slate-50 border border-slate-200 text-slate-500 shrink-0">
                        <x-admin.icon name="upload" class="w-4 h-4" />
                    </span>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-slate-800 truncate">{{ $v->caption ?: 'Video' }}</p>
                        <p class="text-xs text-slate-500 truncate">{{ collect([$v->duration, $v->description, $v->url])->filter()->implode(' · ') }}</p>
                    </div>
                    <x-admin.delete-button :action="route('admin.institutions.media.destroy', [$institution, $v])" />
                </div>
            @empty
                <p class="text-sm text-slate-500 py-2">Hali video qo'shilmagan.</p>
            @endforelse
        </div>

        <form method="POST" action="{{ route('admin.institutions.media.store', $institution) }}" enctype="multipart/form-data" class="border-t border-slate-100 pt-4 space-y-4">
            @csrf
            <input type="hidden" name="type" value="video" />
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <x-admin.input name="caption" label="Sarlavha" placeholder="Masalan, Maktab bilan tanishuv" />
                <x-admin.input name="duration" label="Davomiyligi" placeholder="2:14" />
                <x-admin.input name="description" label="Izoh" placeholder="Ixtiyoriy" />
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-end">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Video fayl</label>
                    <input type="file" name="file" accept="video/*" class="w-full text-sm text-slate-600 file:mr-3 file:rounded-lg file:border-0 file:bg-slate-100 file:px-3 file:py-2 file:text-sm file:font-medium hover:file:bg-slate-200" />
                </div>
                <x-admin.input name="url" label="yoki video havolasi" placeholder="https://youtube.com/..." />
            </div>
            <button type="submit" class="rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-5 py-2.5">Qo'shish</button>
        </form>
    </x-admin.card>
@endsection

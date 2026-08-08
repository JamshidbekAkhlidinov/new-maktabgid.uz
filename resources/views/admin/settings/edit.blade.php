@extends('admin.layout')

@section('title', 'Sozlamalar')

@section('content')
    @php
        use App\Enums\SettingInputType;
        use App\Enums\SettingKey;

        /** @var array<int, SettingKey> $keys */
        $groups = collect($keys)->groupBy(fn (SettingKey $key) => $key->group());
    @endphp

    <x-admin.page-header title="SEO sozlamalari" />

    <x-admin.card class="max-w-2xl">
        <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            @foreach ($groups as $group => $groupKeys)
                <div @class(['border-t border-slate-200 pt-5' => ! $loop->first])>
                    @unless ($loop->first)
                        <h3 class="text-sm font-semibold text-slate-900 mb-1">{{ $group }}</h3>
                    @endunless
                    @if ($hint = SettingKey::groupHint($group))
                        <p class="text-xs text-slate-500 mb-4">{{ $hint }}</p>
                    @endif

                    <div class="space-y-5">
                        @foreach ($groupKeys as $key)
                            @if ($key->inputType() === SettingInputType::Textarea)
                                <x-admin.textarea
                                    :name="$key->value"
                                    :label="$key->label()"
                                    :value="$values[$key->value] ?? null"
                                    :rows="$key === SettingKey::CustomJs ? 6 : 4"
                                    :placeholder="$key->placeholder()"
                                    :class="$key === SettingKey::CustomJs ? 'font-mono text-xs' : ''"
                                />
                            @elseif ($key->inputType() === SettingInputType::Image)
                                <x-admin.image-upload
                                    :name="$key->value"
                                    :label="$key->label()"
                                    :value="$values[$key->value] ?? null"
                                    :hint="$key->hint()"
                                />
                            @else
                                <x-admin.input
                                    :name="$key->value"
                                    :label="$key->label()"
                                    :value="$values[$key->value] ?? null"
                                    :placeholder="$key->placeholder()"
                                />
                            @endif
                        @endforeach
                    </div>
                </div>
            @endforeach

            <div class="flex gap-3 pt-2">
                <button class="rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-5 py-2.5">Saqlash</button>
            </div>
        </form>
    </x-admin.card>
@endsection

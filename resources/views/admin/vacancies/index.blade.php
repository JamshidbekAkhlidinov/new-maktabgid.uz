@extends('admin.layout')

@section('title', 'Vakansiyalar')

@section('content')
    <x-admin.page-header :total="$vacancies->total()" itemLabel="vakansiya" createRoute="admin.vacancies.create" createLabel="Vakansiya qo'shish" />

    <form method="GET" class="flex flex-wrap gap-2 mb-4">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Sarlavha bo'yicha qidirish..."
               class="rounded-lg border border-slate-300 px-3.5 py-2 text-sm w-72 focus:outline-none focus:ring-1 focus:ring-indigo-500" />
        <button class="rounded-lg bg-slate-800 text-white text-sm px-4 py-2">Qidirish</button>
    </form>

    <x-admin.card class="!p-0 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-500 text-left">
                <tr>
                    <th class="px-4 py-3">Lavozim</th>
                    <th class="px-4 py-3">Tashkilot</th>
                    <th class="px-4 py-3">Maosh</th>
                    <th class="px-4 py-3">Bandlik turi</th>
                    <th class="px-4 py-3">Muddati</th>
                    <th class="px-4 py-3 text-right">Amallar</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($vacancies as $vacancy)
                    <tr>
                        <td class="px-4 py-3 font-medium text-slate-800">{{ $vacancy->title }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $vacancy->institution?->name ?? $vacancy->org_name }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $vacancy->salary_range ?? '—' }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $vacancy->employment_type }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $vacancy->expires_at?->format('d.m.Y') ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <x-admin.row-actions
                                :editRoute="route('admin.vacancies.edit', $vacancy)" editPermission="vacancies.update"
                                :deleteRoute="route('admin.vacancies.destroy', $vacancy)" deletePermission="vacancies.delete" />
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-slate-500">Vakansiyalar topilmadi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-admin.card>

    <div class="mt-4">{{ $vacancies->links() }}</div>
@endsection

<?php

namespace Database\Seeders;

use App\Models\Institution;
use App\Models\Vacancy;
use App\Models\VacancyApplication;
use Illuminate\Database\Seeder;

/**
 * Eski `vocations` (haqiqiy vakansiyalar) va `vocation_applications` (nomzod
 * arizalari) jadvallaridan import qiladi.
 */
class LegacyVacancySeeder extends Seeder
{
    /** Eski `type` enum -> vacancies.employment_type ('full'|'part'|'hourly'). */
    private const EMPLOYMENT_TYPE_MAP = [
        'full_time' => 'full',
        'part_time' => 'part',
        'remote' => 'part',
        'contract' => 'hourly',
    ];

    /** Eski `vocation_applications.status` -> vacancy_applications.status ('pending'|'accepted'|'rejected'). */
    private const APPLICATION_STATUS_MAP = [
        'pending' => 'pending',
        'reviewed' => 'accepted',
        'shortlisted' => 'accepted',
        'hired' => 'accepted',
        'rejected' => 'rejected',
    ];

    public function run(): void
    {
        $fixtures = __DIR__.'/legacy_fixtures';

        $vocations = $this->loadJson("$fixtures/vocations.json");
        $applications = $this->loadJson("$fixtures/vocation_applications.json");

        if (empty($vocations)) {
            $this->command?->warn('LegacyVacancySeeder: legacy_fixtures/vocations.json topilmadi — o\'tkazib yuborildi.');

            return;
        }

        $institutions = Institution::whereNotNull('legacy_id')->get(['id', 'legacy_id', 'name'])->keyBy('legacy_id');

        $vacancyLegacyToId = [];

        foreach ($vocations as $v) {
            $legacyId = (int) $v['id'];
            $institution = $institutions[(int) ($v['object_id'] ?? 0)] ?? null;

            $vacancy = Vacancy::updateOrCreate(
                ['legacy_id' => $legacyId],
                [
                    'title' => (string) ($v['title'] ?? "Vakansiya #{$legacyId}"),
                    'institution_id' => $institution?->id,
                    'org_name' => $institution?->name ?? 'MaktabGID',
                    'salary_range' => $this->formatSalary($v['salary_from'] ?? null, $v['salary_to'] ?? null),
                    'employment_type' => self::EMPLOYMENT_TYPE_MAP[$v['type'] ?? ''] ?? 'full',
                    'specialization_key' => null,
                    'posted_by_user_id' => null,
                    'expires_at' => filled($v['deadline'] ?? null) ? $v['deadline'] : null,
                ]
            );

            if (! empty($v['created_at'])) {
                $vacancy->forceFill([
                    'created_at' => $this->toDateTime($v['created_at']),
                    'updated_at' => $this->toDateTime($v['updated_at'] ?? $v['created_at']),
                ])->saveQuietly();
            }

            $vacancyLegacyToId[$legacyId] = $vacancy->id;
        }

        $importedApplications = 0;

        foreach ($applications as $a) {
            $vacancyId = $vacancyLegacyToId[(int) ($a['vocation_id'] ?? 0)] ?? null;

            if (! $vacancyId) {
                continue;
            }

            // Eski rezyume fayli bizda yo'q (faqat nisbiy yo'l saqlangan) — hozircha
            // izohga havola sifatida qo'shiladi, LegacyMedia URL rejimi bilan bir xil
            // mantiq (config('legacy.media_base_url') + eski yo'l).
            $note = trim((string) ($a['cover_letter'] ?? ''));
            if (filled($a['resume_path'] ?? null)) {
                $resumeUrl = rtrim(config('legacy.media_base_url'), '/').'/'.ltrim((string) $a['resume_path'], '/');
                $note = trim($note."\n\n[Eski rezyume fayli]: {$resumeUrl}");
            }

            $application = VacancyApplication::updateOrCreate(
                ['legacy_id' => (int) $a['id']],
                [
                    'vacancy_id' => $vacancyId,
                    'teacher_user_id' => null,
                    'full_name' => (string) ($a['full_name'] ?? ''),
                    'phone' => (string) ($a['phone_number'] ?? ''),
                    'note' => $note !== '' ? $note : null,
                    'status' => self::APPLICATION_STATUS_MAP[$a['status'] ?? ''] ?? 'pending',
                ]
            );

            if (! empty($a['applied_at'])) {
                $application->forceFill([
                    'created_at' => $this->toDateTime($a['applied_at']),
                    'updated_at' => $this->toDateTime($a['updated_at'] ?? $a['applied_at']),
                ])->saveQuietly();
            }

            $importedApplications++;
        }

        $this->command?->info('LegacyVacancySeeder: '.count($vacancyLegacyToId)." ta vakansiya, {$importedApplications} ta ariza import qilindi.");
    }

    private function formatSalary(mixed $from, mixed $to): ?string
    {
        $from = $from !== null ? (float) $from : null;
        $to = $to !== null ? (float) $to : null;

        if (! $from && ! $to) {
            return null;
        }

        $fmt = function (float $v) {
            $millions = $v / 1_000_000;
            $rounded = rtrim(rtrim(number_format($millions, 1, '.', ''), '0'), '.');

            return $rounded === '' ? '0' : $rounded;
        };

        if ($from && $to && $from !== $to) {
            return $fmt($from).' – '.$fmt($to).' mln';
        }

        return $fmt($from ?: $to).' mln';
    }

    /** Eski unix timestamp (int) yoki sana matnini Carbon uchun ISO qatorga aylantiradi. */
    private function toDateTime(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_numeric($value)) {
            return date('Y-m-d H:i:s', (int) $value);
        }

        return (string) $value;
    }

    /** @return array<int, array<string, mixed>> */
    private function loadJson(string $path): array
    {
        if (! is_file($path)) {
            return [];
        }

        $decoded = json_decode((string) file_get_contents($path), true);

        return is_array($decoded) ? $decoded : [];
    }
}

<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Support\Carbon;

/**
 * Suhbat (chat) sahifalarida ishlatiladigan o'zbekcha sana formatlari — APP_LOCALE'dan
 * mustaqil, doim o'zbekcha oy nomi bilan chiqadi (config/app.php'da default 'en').
 * Institution va Parent kabinetlarining Suhbatlar sahifalari bir xil andozada.
 */
trait FormatsUzbekDates
{
    /** "8-iyun, 14:30" ko'rinishidagi sana. */
    private static function uzDate(Carbon $date): string
    {
        return $date->day.'-'.self::uzMonth($date).', '.$date->format('H:i');
    }

    /** "8-iyun" — vaqtsiz, suhbat sahifasidagi sana bo'linuvchilari uchun. */
    private static function uzDayLabel(Carbon $date): string
    {
        return $date->day.'-'.self::uzMonth($date);
    }

    private static function uzMonth(Carbon $date): string
    {
        $months = [1 => 'yanvar', 'fevral', 'mart', 'aprel', 'may', 'iyun', 'iyul', 'avgust', 'sentabr', 'oktabr', 'noyabr', 'dekabr'];

        return $months[$date->month];
    }
}

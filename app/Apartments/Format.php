<?php

namespace App\Apartments;

/** Cell values for the apartment table, Swiss formatting (2'070, 12.03.2027). */
class Format
{
    public static function money(?int $amount): string
    {
        return $amount === null ? '' : number_format($amount, 0, '.', "'");
    }

    /** Areas are rounded up to whole m², like in the Mieterspiegel; 0 stays empty. */
    public static function area(?float $area): string
    {
        return $area ? (string) (int) ceil($area) : '';
    }

    public static function date(?string $date): string
    {
        if (! $date) {
            return '';
        }

        try {
            $parsed = \Carbon\Carbon::parse($date);
        } catch (\Throwable) {
            return $date;
        }

        return $parsed->isPast() ? 'sofort' : $parsed->format('d.m.Y');
    }
}

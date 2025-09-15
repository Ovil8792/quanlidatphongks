<?php

namespace App\Support;

use Carbon\Carbon;

class Validation
{
    public static function required($value, string $message)
    {
        if ($value === null) return $message;
        if (is_string($value) && trim($value) === '') return $message;
        return null;
    }

    public static function maxLength($value, int $max, string $message)
    {
        if (is_string($value) && mb_strlen($value) > $max) return $message;
        return null;
    }

    public static function email($value, string $message)
    {
        if ($value !== null && $value !== '' && !filter_var($value, FILTER_VALIDATE_EMAIL)) return $message;
        return null;
    }

    public static function phoneVN($value, string $message)
    {
        if ($value !== null && $value !== '' && !preg_match('/^0[0-9]{9,10}$/', (string) $value)) return $message;
        return null;
    }

    public static function integerBetween($value, int $min, int $max, string $message)
    {
        if ($value === null || $value === '') return $message;
        if (!is_numeric($value)) return $message;
        $n = (int) $value;
        if ($n < $min || $n > $max) return $message;
        return null;
    }

    public static function dateYmd($value, string $message)
    {
        if ($value === null || $value === '') return $message;
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $value)) return $message;
        return null;
    }

    public static function dateAfter(string $dateIn, string $dateOut, string $message)
    {
        try {
            $cin = Carbon::createFromFormat('Y-m-d', $dateIn)->startOfDay();
            $cout = Carbon::createFromFormat('Y-m-d', $dateOut)->startOfDay();
            if ($cout->lessThanOrEqualTo($cin)) return $message;
        } catch (\Throwable $e) {
            return $message;
        }
        return null;
    }
}



<?php

declare(strict_types=1);

use Carbon\Carbon;

if (! function_exists('minutes_to_duration')) {
    /**
     * Converts an integer number of minutes into duration string.
     * Returns time format H:i from minutes.
     * Formats returned HH:MM, HHH:MM, HH, or HHH.
     *
     * @param  int|float $minutes
     * @param  bool      $abbreviate
     * @return string
     */
    function minutes_to_duration(int|float $minutes, bool $abbreviate = false): string
    {
        $sign = $minutes >= 0 ? '' : '-';
        $minutes = abs($minutes);

        $hours = (string) (int)($minutes / 60);
        $mins = (string) round(fmod($minutes, 60));

        if (strlen($mins) == 1)
            $mins = '0' . $mins;

        if (strlen($hours) == 1)
            $hours = '0' . $hours;

        if ($abbreviate && $mins == '00')
            return $sign.$hours;

        return $sign.$hours.':'.$mins;
    }
}

if (! function_exists('time_to_minutes')) {
    /**
     * Transform time into minutes.
     * Seconds are not counted.
     * Returns minutes from time format H:i:s|H:i
     *
     * @param  mixed  $time
     *
     * @return int
     */
    function time_to_minutes(mixed $time): int
    {
        if ($time instanceof DateTimeInterface) {
            $time = $time->format('H:i');
        }

        $hm = explode(':', $time);
        return ($hm[0] * 60) + $hm[1];
    }
}

if (! function_exists('sum_time')) {
    /**
     * Returns the sum of times.
     *
     * @param  array  $entitiy
     *
     * @return string
     */
    function sum_time(array $entitiy): string
    {
        $time = (array)$entitiy;
        $time = array_filter($time, function ($item) {
            return !in_array($item, ['00:00', '00:00:00', '0:00:00']);
        });
        $begin = Carbon::createFromFormat('H:i:s', '00:00:00');
        $end = clone $begin;

        foreach ($time as $element) {
            $elementParsed = Carbon::parse($element)->format('H:i:s');
            $dateTime = Carbon::createFromFormat('H:i:s', $elementParsed);
            $end->addHours((int)$dateTime->format('H'))
                ->addMinutes((int)$dateTime->format('i'))
                ->addSeconds((int)$dateTime->format('s'));
        }

        return sprintf(
            '%s:%s:%s',
            (int)$end->diffInHours($begin, true),
            $end->format('i'),
            $end->format('s')
        );
    }
}

if (! function_exists('to_decimal_time')) {
    /**
     * Transform H:i format time to decimal time.
     * Seconds are not counted.
     * Ex 01:30 worked hours into 1.30
     *
     * @param  mixed  $time / in format H:i:s or H:i
     * @param  bool   $transform
     *
     * @return float
    */
    function to_decimal_time(mixed $time, bool $transofrm = false): float
    {
        if($transofrm) {
            $time = minutes_to_duration((int)$time);
        }

        if ($time instanceof DateTimeInterface) {
            $time = $time->format('H:i');
        }

        $hm = explode(":", $time);
        $decimal = ($hm[0] + ($hm[1]/60));

        return round($decimal, 2);
    }
}

if (! function_exists('valid_template_text')) {
    /**
     * Used to check text-based user input.
     * We identify these parts by 3 "stop sign" emojis (aka "octagonal sign" U+1F6D1).
     *
     * @param  string $text
     * @param  string|null $template
     *
     * @return bool $valid
     */
    function valid_template_text(string $text, string $template = '🛑🛑🛑'): bool
    {
        $valid = strpos($text, $template) === false; // no 3 "stop sign" emojis in a row.
        return $valid;
    }
}

if (! function_exists('is_valid_date')) {
    /**
     * Is used to check user input to validate a date.
     *
     * @param  mixed $date
     *
     * @return bool
     */
    function is_valid_date(mixed $date): bool
    {
        if ($date instanceof DateTimeInterface) {
            return true;
        }

        try {
            if ((! is_string($date) && ! is_numeric($date)) || strtotime($date) === false) {
                return false;
            }
        } catch (Exception) {
            return false;
        }

        $value = date_parse($date);

        return checkdate($value['month'], $value['day'], $value['year']);
    }
}

if (!function_exists('get_package_version')) {
    /**
     * Is used to get package version.
     *
     * @param string $package
     *
     * @return string|null
     */
    function get_package_version($package): string|null
    {
        $file = base_path('composer.lock');
        $composer_packages = json_decode(file_get_contents($file), true)['packages'];

        foreach ($composer_packages as $composer_package) {
            if ($composer_package['name'] == $package) {
                return $package['version'];
            }
        }

        return null;
    }
}

if (!function_exists('has_package')) {
    /**
     * Is used to check if package is installed.
     *
     * @param string $package
     *
     * @return bool
     */
    function has_package($package): bool
    {
        $file = base_path('composer.lock');
        $composer_packages = json_decode(file_get_contents($file), true)['packages'];

        foreach ($composer_packages as $composer_package) {
            if ($composer_package['name'] == $package) {
                return true;
            }
        }

        return false;
    }
}

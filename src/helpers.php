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
     * @param  \DateTimeInterface|string  $time
     * @return int
     */
    function time_to_minutes(\DateTimeInterface|string $time): int
    {
        if ($time instanceof DateTimeInterface) {
            $time = $time->format('H:i');
        }

        $hm = explode(':', $time);
        return ($hm[0] * 60) + $hm[1];
    }
}

if (! function_exists('minutes_to_hours')) {
    /**
     * Converts an integer number of minutes into hours string.
     * Returns time format H from minutes.
     * Formats returned HH, or HHH.
     *
     * @param  int|float $minutes
     * @param  bool      $abbreviate
     * @return string
     */
    function minutes_to_hours(int|float $minutes, bool $abbreviate = false): string
    {
        $sign = $minutes >= 0 ? '' : '-';
        $minutes = abs($minutes);

        $hours = (string) (int)($minutes / 60);

        if (strlen($hours) == 1)
            $hours = '0' . $hours;

        if ($abbreviate)
            return $sign.$hours;

        return $sign.$hours;
    }
}

if (! function_exists('sum_time')) {
    /**
     * Returns the sum of times.
     *
     * @param  array  $entitiy
     * @param  bool   $carbon
     * @return string
     */
    function sum_time(array $entitiy, bool $carbon = false): string
    {
        $a = '01:00:00';
        $b = '01:00:00';
        $c = '01:00:00';

        //convert the $a in carbon instance.
        //convert $b and $c in integer, you can add only integer with carbon.
        $d = Carbon::createFromFormat('H:i:s',$a)->addHours(intval($b))->addHours((intval($c)));

        //convert the time "45:00:00" to carbon
        $e = Carbon::createFromFormat('H:i:s','45:00:00');

        //return the difference
        //dd($e->diffInHours($d));

        //work, up to this line
        if($carbon) {
            $hh = 0; $mm = 0; $ss = 0;

            foreach ($entitiy as $time) {
                sscanf( $time, '%d:%d:%d', $hours, $mins, $secs);
                $hh += $hours;
                $mm += $mins;
                $ss += $secs;
            }

            $mm += floor( $ss / 60 ); $ss = $ss % 60;
            $hh += floor( $mm / 60 ); $mm = $mm % 60;
            return sprintf('%02d:%02d:%02d', $hh, $mm, $ss);
        }

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
     * @param  \DateTimeInterface|string|int  $time / in format H:i:s or H:i
     * @param  bool   $transform
     * @return float
    */
    function to_decimal_time(\DateTimeInterface|string|int $time, bool $transofrm = false): float
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
     * @return bool
     */
    function valid_template_text(string $text, string $template = '🛑🛑🛑'): bool
    {
        return strpos($text, $template) === false; // no 3 "stop sign" emojis in a row.
    }
}

if (! function_exists('is_valid_date')) {
    /**
     * Is used to check user input to validate a date.
     *
     * @param  \DateTimeInterface|string $date
     * @return bool
     */
    function is_valid_date(\DateTimeInterface|string $date): bool
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
     * @param  string $package
     * @return string|null
     */
    function get_package_version($package): string|null
    {
        $file = base_path('composer.lock');
        $composer_packages = json_decode(file_get_contents($file), true)['packages'];

        foreach ($composer_packages as $composer_package) {
            if ($composer_package['name'] == $package) {
                return $composer_package['version'];
            }
        }

        return null;
    }
}

if (!function_exists('has_package')) {
    /**
     * Is used to check if package is installed.
     *
     * @param  string $package
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

if (!function_exists('object_to_array')) {
    /**
     * Convert stdClass Object to Array.
     *
     * @param  $data
     * @return array
     */
    function object_to_array($data): mixed
    {
        if (is_object($data)) {
            $data = get_object_vars($data);
        }

        if (is_array($data)) {
            return array_map(__FUNCTION__, $data);
        }
        else {
            return $data;
        }
    }
}

if (!function_exists('array_to_object')) {
    /**
     * Convert Array to stdClass Object.
     *
     * @param  $data
     * @return object
     */
    function array_to_object($data): mixed
    {
        if (is_array($data)) {
            return (object)array_map(__FUNCTION__, $data);
        }
        else {
            return $data;
        }
    }
}

if (!function_exists('count_true')) {
    /**
     * Counts all elements in an array or in a object.
     *
     * @param  array|object $data
     * @return int
     */
    function count_values(array|object $data): int
    {
        if (is_object($data)) {
            $data = object_to_array($data);
        }

        return count(array_filter($data));
    }
}

if (!function_exists('str_contains_any')) {
    /**
     * Check if string contains any from an array/object.
     *
     * @param  string $haystack
     * @param  array|object $needles
     * @return array
     */
    function str_contains_any(string $haystack, array $needles): array
    {
        $contains = [];

        if (is_object($needles)) {
            $needles = object_to_array($needles);
        }

        foreach ($needles as $needle) {
            if (stristr($haystack, $needle) !== false) {
                $contains[] = $needle;
            }
        }

        return $contains;
    }
}

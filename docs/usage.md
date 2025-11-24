---
title: How to use package
description: How to use package
github: https://github.com/zaimealabs/helpers/edit/main/docs
onThisArticle: true
sidebar: true
rightbar: true
---

# Helpers Usage

[[TOC]]

## Usage

### Work in progress

```php
    /**
     * Converts an integer number of minutes into duration string.
     * Returns time format H:i from minutes.
     * Formats returned HH:MM, HHH:MM, HH, or HHH.
     *
     * @param  int|float $minutes
     * @param  bool      $abbreviate
     * @return string
     */
    minutes_to_duration(int|float $minutes, bool $abbreviate = false)
```

```php
    /**
     * Transform time into minutes.
     * Seconds are not counted.
     * Returns minutes from time format H:i:s|H:i
     *
     * @param  \DateTimeInterface|string  $time
     * @return int
     */
    time_to_minutes(\DateTimeInterface|string $time)
```

```php
    /**
     * Converts an integer number of minutes into hours string.
     * Returns time format H from minutes.
     * Formats returned HH, or HHH.
     *
     * @param  int|float $minutes
     * @param  bool      $abbreviate
     * @return string
     */
    minutes_to_hours(int|float $minutes, bool $abbreviate = false)
```

```php
    /**
     * Returns the sum of times.
     *
     * @param  array  $entitiy
     * @param  bool   $carbon
     * @return string
     */
    sum_time(array $entitiy, bool $carbon = false)
```

```php
    /**
     * Transform H:i format time to decimal time.
     * Seconds are not counted.
     * Ex 01:30 worked hours into 1.30
     *
     * @param  \DateTimeInterface|string|int  $time / in format H:i:s or H:i
     * @param  bool   $transform
     * @return float
    */
    to_decimal_time(\DateTimeInterface|string|int $time, bool $transofrm = false)
```

```php
    /**
     * Used to check text-based user input.
     * We identify these parts by 3 "stop sign" emojis (aka "octagonal sign" U+1F6D1).
     *
     * @param  string $text
     * @param  string|null $template
     * @return bool
     */
    valid_template_text(string $text, string $template = '🛑🛑🛑')
```

```php
    /**
     * Is used to check user input to validate a date.
     *
     * @param  \DateTimeInterface|string $date
     * @return bool
     */
    is_valid_date(\DateTimeInterface|string $date)
```

```php
    /**
     * Is used to get package version.
     *
     * @param  string $package
     * @return string|null
     */
    get_package_version($package)
```

```php
    /**
     * Is used to check if package is installed.
     *
     * @param  string $package
     * @return bool
     */
    has_package($package)
```

```php
    /**
     * Convert stdClass Object to Array.
     *
     * @param  $data
     * @return array
     */
    object_to_array($data)
```

```php
    /**
     * Convert Array to stdClass Object.
     *
     * @param  $data
     * @return object
     */
    array_to_object($data)
```

```php
    /**
     * Counts all elements in an array or in a object.
     *
     * @param  array|object $data
     * @return int
     */
    count_values(array|object $data)
```

```php
    /**
     * Check if string contains any from an array/object.
     *
     * @param  string $haystack
     * @param  array|object $needles
     * @return array
     */
    function str_contains_any(string $haystack, array $needles): array
```

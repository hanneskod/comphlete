<?php

declare(strict_types = 1);

namespace hanneskod\comphlete;

class Helper
{
    /**
     * @param array<mixed> $values
     * @return array<mixed>
     */
    public static function flatten(array $values): array
    {
        return (array)array_reduce(
            $values,
            function (array $carry, $item) {
                if (!$item) {
                    return $carry;
                }

                if (is_array($item)) {
                    return array_merge($carry, self::flatten($item));
                }

                $carry[] = $item;
                return $carry;
            },
            []
        );
    }

    /**
     * Keep values starting with string
     *
     * @param  string[] $values
     * @return string[]
     */
    public static function filter(array $values, string $start): array
    {
        if (!$start) {
            return $values;
        }

        $filtered = [];

        foreach ($values as $value) {
            if (strpos($value, $start) === 0) {
                $filtered[] = $value;
            }
        }

        return $filtered;
    }

    /**
     * Add a trailing space to all array values
     *
     * @param  string[] $values
     * @return string[]
     */
    public static function addTrailingSpaces(array $values): array
    {
        return array_map(
            function (string $value) {
                return $value . ' ';
            },
            $values
        );
    }

    /**
     * Dump values using ifs as separator
     *
     * @param string[] $values
     */
    public static function dump(array $values, string $ifs = '|'): string
    {
        return implode($ifs, $values);
    }

    /**
     * Explode values based on non-alphanum characters
     *
     * @param  string[] $values
     * @return string[]
     */
    public static function explode(array $values): array
    {
        $exploded = [];

        foreach ($values as $value) {
            $exploded[$value] = $value;

            foreach ((array)preg_split('/[^a-z0-9 ]/i', $value, 0, PREG_SPLIT_NO_EMPTY) as $part) {
                $part = (string)$part;
                $exploded[$part] = $part;
            }
        }

        return array_values($exploded);
    }
}

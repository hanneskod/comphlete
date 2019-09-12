<?php

declare(strict_types = 1);

namespace hanneskod\comphlete;

class Helper
{
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
     * @param  string[] $values
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
     * @param  string[] $values
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
}

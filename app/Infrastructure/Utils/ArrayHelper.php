<?php

namespace App\Infrastructure\Utils;

class ArrayHelper
{
    public static function removeEmptyOrNullElements(array $array, array $extraFilters = [])
    {
        return collect($array)->filter(function ($value, $key) use ($extraFilters): bool {
            $include = false;

            foreach ($extraFilters as $filter) {
                if ($filter['action'] === 'exclude' && (isset($filter['key']) && $key === $filter['key']) || (isset($filter['value']) && $value === $filter['value'])) {
                    $include = false;
                }

                if ($filter['action'] === 'include' && (isset($filter['key']) && $key === $filter['key']) || (isset($filter['value']) && $value === $filter['value'])) {
                    $include = true;
                }
            }

            return $include || (!is_null($value) && $value !== '');
        })->toArray();
    }
}

<?php

namespace App\Domain\Traits;

trait EnumToArray
{
    public static function names(): array
    {
        return array_column(static::cases(), 'name');
    }

    public static function values(): array
    {
        return array_column(static::cases(), 'value');
    }

    public static function array(): array
    {
        return array_combine(static::values(), static::names());
    }
}

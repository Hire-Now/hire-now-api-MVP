<?php

namespace App\Domain\Enums;

use App\Domain\Traits\EnumToArray;

enum ElementStatus: string
{
    use EnumToArray;

    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case DELETED = 'deleted';
}

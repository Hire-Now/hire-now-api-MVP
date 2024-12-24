<?php

namespace App\Domain\Enums;

use App\Domain\Traits\EnumToArray;

enum Roles: string
{
    use EnumToArray;

    case ADMINISTRATOR = 'administrator';
    case MODERATOR = 'moderator';
    case RECRUITER = 'recruiter';
    case EXECUTIVE = 'executive';
    case CANDIDATE = 'candidate';
}

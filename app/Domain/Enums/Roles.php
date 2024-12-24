<?php

namespace App\Domain\Enums;

enum Roles
{
    case ADMINISTRATOR;
    case MODERATOR;
    case RECRUITER;
    case EXECUTIVE;
    case CANDIDATE;
}

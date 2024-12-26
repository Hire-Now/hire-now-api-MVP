<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class EmailVerificationToken extends Model
{
    use HasUuids, MustVerifyEmail;

    protected $fillable = [
        'email',
        'token',
        'email_verified_at',
    ];
}

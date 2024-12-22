<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    protected $table = 'Candidates';

    protected $fillable = [ 'name', 'email', 'skills' ];

    public $timestamps = true;
}

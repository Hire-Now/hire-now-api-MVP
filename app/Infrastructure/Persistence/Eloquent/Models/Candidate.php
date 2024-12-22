<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    protected $table = 'Candidates'; // Tabla en la base de datos

    protected $fillable = ['name']; // Atributos que pueden ser asignados en masa

    public $timestamps = true; // Habilitar timestamps si es necesario
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarefa extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'nome',
        'projeto_id',
        'inicio',
        'fim',
        'status'
    ];

}
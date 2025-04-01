<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{
    /** @use HasFactory<\Database\Factories\ClasseFactory> */
    use HasFactory;
    protected $table = 'classes';

    protected $fillable = [
        'cin',
        'code_class',
        'filier_name',
        's_fname',
        's_lname',
        'age',

    ];
}

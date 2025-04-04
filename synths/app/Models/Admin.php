<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    protected static $guards = 'admin';
    /** @use HasFactory<\Database\Factories\AdminFactory> */
    use HasFactory;
}

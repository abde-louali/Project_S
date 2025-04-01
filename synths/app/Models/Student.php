<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'cin',
        's_fname',
        's_lname',
        'id_card_img',
        'bac_img',
        'birth_img',
        'code_class',
        'filier_name'
    ];

    /**
     * Get the ID card image as readable data
     */
    public function getIdCardImageAttribute()
    {
        return $this->id_card_img ? $this->id_card_img : null;
    }

    /**
     * Get the BAC image as readable data
     */
    public function getBacImageAttribute()
    {
        return $this->bac_img ? $this->bac_img : null;
    }

    /**
     * Get the birth certificate image as readable data
     */
    public function getBirthImageAttribute()
    {
        return $this->birth_img ? $this->birth_img : null;
    }
}

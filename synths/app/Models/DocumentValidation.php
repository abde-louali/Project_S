<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentValidation extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'cin',
        'student_name',
        'verified_name',
        'is_correct',
        'file_details',
        'errors',
        'filier_name',
        'class_name',
        'validation_date'
    ];

    protected $casts = [
        'file_details' => 'array',
        'errors' => 'array',
        'is_correct' => 'boolean',
        'validation_date' => 'datetime'
    ];

    /**
     * Get the student that owns the document validation.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}

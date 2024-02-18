<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Classes extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function section()
    {
        return $this->hasMany(Section::class, 'class_id', 'id');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'class_id');
    }
}

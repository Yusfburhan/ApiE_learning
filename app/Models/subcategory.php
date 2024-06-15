<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class subcategory extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function subcourses()
    {
        return $this->hasMany(Course::class, 'subcategory_id');
    }
    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
